<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 31/03/2017
 * Time: 20:55
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Visitor;


use ToolsBundle\Services\ExcelNodeVisitor\Node\AttributeNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\CollectionNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\EntityNode;

abstract class AbstractNodeVisitor
{
    public abstract function visitEntityNode(EntityNode $node);
    public abstract function visitAttributeNode(AttributeNode $node);
    public abstract function visitCollectionNode(CollectionNode $node);
}