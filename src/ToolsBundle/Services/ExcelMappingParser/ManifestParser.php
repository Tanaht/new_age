<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 31/03/2017
 * Time: 21:08
 */

namespace ToolsBundle\Services\ExcelMappingParser;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use ToolsBundle\Services\ExcelMappingParser\Exception\InvalidManifestFileException;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AbstractNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\NodeFactory;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\CollectionReferencedNodeVisitor;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\PrintTreeVisitor;

class ManifestParser
{
    /**
     * @var Container $container
     */
    private $container;

    /**
     * @var ExcelManifest
     */
    private $manifest;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->manifest = new ExcelManifest();
    }

    /**
     * Returns a parameterBagInstance that describe the manifest
     * @param $filepath
     * @throws InvalidManifestFileException
     * @return ExcelManifest
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
        return $this->manifest;
    }

    /**
     * @param array $sheets
     */
    private function parseSheets(array $sheets) {
        foreach ($sheets as $sheetName => $sheetMapping) {
            $this->parseSheet($sheetName, $sheetMapping);
        }
    }

    /**
     * @param array $sheet
     */
    private function parseSheet($sheetName, array $sheet) {
        $columnsOffset = 0;
        foreach ($sheet as $nodeIdentifier => $nodeManifest) {
            $columnsOffset += AbstractNode::TABLE_OFFSET + $this->parseEntity($sheetName, $nodeIdentifier, $nodeManifest, $columnsOffset);
        }
    }

    /**
     * @param string $nodeIdentifier
     * @param array $nodeManifest
     * @return int the width of the Entity Node
     */
    private function parseEntity($sheetName, $nodeIdentifier, array $nodeManifest, $columnsOffset) {

        $factory = NodeFactory::getFactory($this->container);

        $node = $factory->createRootNode($nodeIdentifier, $nodeManifest);

        $this->manifest->addEntity($sheetName, $node->getIdentifier(), $columnsOffset, $node);

        return $node->getWidth();
    }
}