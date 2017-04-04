<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 01/04/2017
 * Time: 23:18
 */

namespace ToolsBundle\Services\ExcelMappingParser;



use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;
use ToolsBundle\Services\ExcelNodeVisitor\Node\EntityNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\RootNode;

class ExcelManifest
{
    /**
     * @var ParameterBag
     */
    private $manifest;

    public function __construct()
    {
        $this->manifest = new ParameterBag(['entities' => new ParameterBag(), 'sheets' => new ParameterBag()]);
    }


    public function addEntity($sheet, $identifier, $offset, RootNode $node) {
        $this->manifest->get('entities')->set($identifier, $node);

        if(!$this->manifest->get('sheets')->has($sheet))
            $this->manifest->get('sheets')->set($sheet, new ParameterBag());

        $this->manifest->get('sheets')->get($sheet)->set($identifier, new ParameterBag(['identifier' => $identifier, 'offset' => $offset]));
    }

    /**
     * Return Infos about the entity identifier in parameter
     * @param $identifier
     * @return ParameterBag
     * @throws InvalidArgumentException if the identifier is not found in this manifest
     */
    public function getEntityInfos($identifier) {
        foreach ($this->manifest->get('sheets')->getIterator() as $sheetName => $sheetInfos) {
            /** @var ParameterBag $sheetInfos */
            if($sheetInfos->has($identifier)) {
                $sheetInfos->get($identifier)->set('sheet', $sheetName);
                return $sheetInfos->get($identifier);
            }
        }

        throw new InvalidArgumentException("Cannot retrieve entity named:" . $identifier  . " in manifest description");
    }

    /**
     * Update Infos about entity with given key/value pair
     * @param $identifier
     * @param $key
     * @param $value
     * @throws InvalidArgumentException if the identifier is not found in the manifest
     */
    public function updateEntityInfos($identifier, $key, $value) {
        foreach ($this->manifest->get('sheets')->getIterator() as $sheetName => $sheetInfos) {
            /** @var ParameterBag $sheetInfos */
            if($sheetInfos->has($identifier)) {
                $sheetInfos->get($identifier)->set('sheet', $sheetName);
                $sheetInfos->get($identifier)->set($key, $value);
                return;
            }
        }

        throw new InvalidArgumentException("Cannot retrieve entity named:" . $identifier  . " in manifest description");
    }

    /**
     * @return ParameterBag entities
     */
    public function getEntityNodes() {
        return $this->manifest->get('entities');
    }

    /**
     * @return ParameterBag
     */
    public function getSheets() {
        return $this->manifest->get('sheets');
    }


    /**
     * @param $identifier
     * @return EntityNode
     * @throws InvalidArgumentException if the identifier is not found in this manifest
     */
    public function getEntityNode($identifier) {
        if(!$this->manifest->get('entities')->has($identifier))
            throw new InvalidArgumentException("Cannot retrieve entity named:" . $identifier  . " in manifest description");

        return $this->manifest->get('entities')->get($identifier);
    }

}