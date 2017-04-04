<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 03/04/2017
 * Time: 20:32
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;


use ToolsBundle\Services\ExcelNodeVisitor\Visitor\AbstractNodeVisitor;

abstract class AbstractLeaf extends AbstractNode2
{
    public function getWidth()
    {
        return 1;
    }
}