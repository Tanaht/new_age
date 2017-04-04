<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 31/03/2017
 * Time: 20:55
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Visitor;


use ToolsBundle\Services\ExcelNodeVisitor\Node\CollectionNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\EntityNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\PropertyLeaf;
use ToolsBundle\Services\ExcelNodeVisitor\Node\RootNode;

abstract class AbstractNodeVisitor
{
    public abstract function visitRootNode(RootNode $node);
    public abstract function visitCollectionNode(CollectionNode $node);
    public abstract function visitPropertyLeaf(PropertyLeaf $node);
    public abstract function visitEntityNode(EntityNode $node);
}