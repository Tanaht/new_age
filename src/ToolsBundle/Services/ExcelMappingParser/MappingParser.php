<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 31/03/2017
 * Time: 21:08
 */

namespace ToolsBundle\Services\ExcelMappingParser;


use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use ToolsBundle\Services\ExcelMappingParser\Exception\InvalidManifestFileException;
use ToolsBundle\Services\ExcelNodeVisitor\Node\EntityNode;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\InstanciationNodeVisitor;

class MappingParser
{
    /**
     * @var Container $container
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $filepath
     * @throws InvalidManifestFileException
     */
    public function parse($filepath) {
        $manifest = [];
        try {
            $manifest = Yaml::parse(file_get_contents($filepath));
        } catch (ParseException $e) {
            printf("Unable to parse the YAML string: %s", $e->getMessage());
        }


        if(!array_key_exists('sheets', $manifest)) {
            throw new InvalidManifestFileException("Unable to find root key 'sheets' at $filepath");
        }

        $this->parseSheets($manifest['sheets']);
    }

    /**
     * @param array $sheets
     */
    private function parseSheets(array $sheets) {
        foreach ($sheets as $sheetName => $sheetMapping) {
            $this->parseSheet($sheetMapping);
        }
    }

    /**
     * @param array $sheet
     */
    private function parseSheet(array $sheet) {
        foreach ($sheet as $entityId => $entityMapping) {
            $this->parseEntity($entityId, $entityMapping);
        }
    }

    /**
     * @param string $entityId
     * @param array $entity
     */
    private function parseEntity($entityId, array $entity) {
        $visitor = new InstanciationNodeVisitor($this->container, $entity);
        $rootNode = new EntityNode($entityId);

        $rootNode->accept($visitor);
    }
}