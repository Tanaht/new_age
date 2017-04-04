<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 04/04/2017
 * Time: 17:56
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Visitor;


use ToolsBundle\Services\ExcelNodeVisitor\Node\CollectionNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\EntityNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\PropertyLeaf;
use ToolsBundle\Services\ExcelNodeVisitor\Node\RootNode;

class PrintTreeVisitor extends AbstractNodeVisitor
{

    public function visitRootNode(RootNode $node)
    {

        $tabs = "";

        for ($i = 0 ; $i < $node->getDepth() ; $i++) {
            $tabs .= "\t";
        }
        echo $tabs . $node . PHP_EOL;

        foreach ($node->getProperties() as $property) {
            $property->accept($this);
        }
    }

    public function visitCollectionNode(CollectionNode $node)
    {
        $tabs = "";

        for ($i = 0 ; $i < $node->getDepth() ; $i++) {
            $tabs .= "\t";
        }
        echo $tabs . $node . PHP_EOL;
        foreach ($node->getProperties() as $property) {
            $property->accept($this);
        }
    }

    public function visitPropertyLeaf(PropertyLeaf $node)
    {
        $tabs = "";

        for ($i = 0 ; $i < $node->getDepth() ; $i++) {
            $tabs .= "\t";
        }
        echo $tabs . $node . PHP_EOL;
    }

    public function visitEntityNode(EntityNode $node)
    {
        $tabs = "";

        for ($i = 0 ; $i < $node->getDepth() ; $i++) {
            $tabs .= "\t";
        }
        echo $tabs . $node . PHP_EOL;
        foreach ($node->getProperties() as $property) {
            $property->accept($this);
        }
    }
}