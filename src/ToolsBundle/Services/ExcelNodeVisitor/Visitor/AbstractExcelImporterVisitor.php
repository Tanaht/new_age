<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 15/04/2017
 * Time: 17:09
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Visitor;


use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\ParameterBag;
use ToolsBundle\Services\ExcelNodeVisitor\Node\RootNode;

abstract class AbstractExcelImporterVisitor extends AbstractExcelVisitor
{
    /**
     * @param RootNode $rootNode
     * @param ParameterBag $nodeInfo
     * @return Collection
     */
    public abstract function importExcelTable(RootNode $rootNode, ParameterBag $nodeInfo);
}