<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 01/04/2017
 * Time: 14:38
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Visitor;


use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\ParameterBag;
use ToolsBundle\Services\ExcelMappingParser\ExcelManifest;
use ToolsBundle\Services\ExcelMappingParser\Exception\InvalidManifestFileException;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AbstractNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AttributeNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\CollectionNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\EntityNode;

/**
 * This Visitor serve to map the collectionNode with their referenced EntityNode they asked
 * Class CollectionReferencedNodeVisitor
 * @package ToolsBundle\Services\ExcelNodeVisitor\Visitor
 */
class CollectionReferencedNodeVisitor extends AbstractNodeVisitor
{

    /**
     * @var ExcelManifest
     */
    private $manifest;

    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container, ExcelManifest $manifest)
    {
        $this->container = $container;
        $this->manifest = $manifest;
    }

    public function visitEntityNode(EntityNode $node)
    {
        $node->getChildrens()->forAll(function($key, AbstractNode $children) {
            $children->accept($this);
            return true;
        });
    }

    public function visitAttributeNode(AttributeNode $node)
    {
    }

    public function visitCollectionNode(CollectionNode $node)
    {
        if(!$this->manifest->getEntityNodes()->has($node->getReference())) {
            throw new InvalidManifestFileException("The Referenced Entity with identifier: [" . $node->getReference() . "] doesn't exist, on the Collection Type identified by: " . $node);
        }
        else {
            $this->manifest->updateEntityInfos($node->getReference(), 'reference', true);
            $node->setReferencedNode($this->manifest->getEntityNode($node->getReference()));
        }
    }
}