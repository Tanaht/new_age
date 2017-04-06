<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 06/04/2017
 * Time: 13:01
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Visitor;

use Doctrine\ORM\Query;
use PHPExcel_Worksheet;
use Symfony\Component\HttpFoundation\ParameterBag;
use ToolsBundle\Services\ExcelNodeVisitor\Node\RootNode;
use PHPExcel_Style_Alignment;

abstract class AbstractExcelExporterVisitor extends AbstractNodeVisitor
{
    /**
     * @var PHPExcel_Worksheet
     */
    private $worksheet;

    /**
     * @return PHPExcel_Worksheet
     */
    public function getWorksheet()
    {
        return $this->worksheet;
    }

    public abstract function exportExcelTable(Query $query, RootNode $rootNode, ParameterBag $nodeInfo);

    /**
     * @param PHPExcel_Worksheet $worksheet
     */
    public function setWorksheet($worksheet)
    {
        $this->worksheet = $worksheet;
    }


}