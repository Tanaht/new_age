<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 15/04/2017
 * Time: 17:08
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Visitor;


use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\ParameterBag;
use ToolsBundle\Services\ExcelNodeVisitor\Node\RootNode;

abstract class AbstractExcelExporterVisitor extends AbstractExcelVisitor
{
    public abstract function exportExcelTable(Query $query, RootNode $rootNode, ParameterBag $nodeInfo);
}