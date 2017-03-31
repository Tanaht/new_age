<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 31/03/2017
 * Time: 20:55
 */

namespace ToolsBundle\Services\ExcelRowVisitor;


abstract class NodeVisitor
{
    public abstract function visit(AbstractNode $node);
}