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
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use ToolsBundle\Services\ExcelMappingParser\Exception\InvalidManifestFileException;
use ToolsBundle\Services\ExcelNodeVisitor\Node\EntityNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\NodeFactory;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\InstanciationNodeVisitor;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\CollectionReferencedNodeVisitor;

class ManifestParser
{
    /**
     * @var Container $container
     */
    private $container;

    /**
     * @var ParameterBag
     */
    private $excelFormat;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->excelFormat = new ParameterBag();
        $this->excelFormat->set('entities', new ParameterBag());
        $this->excelFormat->set('sheets', new ParameterBag());
    }

    /**
     * Returns a parameterBagInstance that describe the manifest
     * @param $filepath
     * @throws InvalidManifestFileException
     * @return ParameterBag
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

        $visitor = new CollectionReferencedNodeVisitor($this->container, $this->excelFormat);
        return $this->excelFormat;
    }

    /**
     * @param array $sheets
     */
    private function parseSheets(array $sheets) {
        foreach ($sheets as $sheetName => $sheetMapping) {
            $this->excelFormat->get('sheets')->set($sheetName, $this->parseSheet($sheetMapping));
        }
    }

    /**
     * @param array $sheet
     */
    private function parseSheet(array $sheet) {
        $entityIdentifiers = [];

        foreach ($sheet as $nodeIdentifier => $nodeManifest) {
            $this->parseEntity($nodeIdentifier, $nodeManifest);
            $entityIdentifiers[] = $nodeIdentifier;
        }

        return $entityIdentifiers;
    }

    /**
     * @param string $nodeIdentifier
     * @param array $nodeManifest
     */
    private function parseEntity($nodeIdentifier, array $nodeManifest) {

        $factory = NodeFactory::getFactory($this->container);


        $node = $factory->createEntityNode($nodeIdentifier, $nodeManifest);

        dump("node width: " . $node->getWidth());
        dump("node height: " . $node->getHeight());
        $this->excelFormat->get('entities')->set($nodeIdentifier, $node);
    }
}