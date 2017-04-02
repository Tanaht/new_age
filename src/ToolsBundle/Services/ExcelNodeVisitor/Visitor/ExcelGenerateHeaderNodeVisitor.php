<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 01/04/2017
 * Time: 17:49
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Visitor;


use Symfony\Component\HttpFoundation\ParameterBag;
use ToolsBundle\Services\ExcelMappingParser\ExcelManifest;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AbstractNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AttributeNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\CollectionNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\EntityNode;
use PHPExcel_WorkSheet;

class ExcelGenerateHeaderNodeVisitor extends AbstractNodeVisitor
{
    /**
     * @var ExcelManifest
     */
    private $manifest;

    /**
     * @var PHPExcel_Worksheet
     */
    private $worksheet;



    public function __construct(ExcelManifest $manifest)
    {
        $this->manifest = $manifest;
    }

    public function setWorkSheet(PHPExcel_Worksheet $workSheet) {
        $this->worksheet = $workSheet;
    }

    public function getWorkSheet() {
        return $this->worksheet;
    }

    public function visitEntityNode(EntityNode $node)
    {
        /** @var ParameterBag $infos */
        $infos = $this->manifest->getEntityInfos($node->getIdentifier());
        $row = $node->getDepth();
        $startCol = $node->getCol($infos->get('offset'));
        $endCol = $startCol + $node->getWidth() - 1;

        $start = $this->worksheet->getCellByColumnAndRow($startCol, $row)->getCoordinate();
        $end = $this->worksheet->getCellByColumnAndRow($endCol, $row)->getCoordinate();

        $this->worksheet->mergeCells($start.":".$end)->setCellValue($start, $node->getLabel());

        foreach($node->getChildrens()->getIterator() as $childrenNode) {
            /** @var AbstractNode $childrenNode */
            $childrenNode->accept($this);
        }

    }

    public function visitAttributeNode(AttributeNode $node)
    {
        /** @var ParameterBag $infos */
        $infos = $this->manifest->getEntityInfos($node->getRootNode()->getIdentifier());

        $row = $node->getDepth();
        $col = $node->getCol($infos->get('offset'));

        $this->worksheet->getCellByColumnAndRow($col, $row)->setValue($node->getLabel());
    }

    public function visitCollectionNode(CollectionNode $node)
    {
        /** @var ParameterBag $infos */
        $infos = $this->manifest->getEntityInfos($node->getRootNode()->getIdentifier());

        $row = $node->getDepth();
        $col = $node->getCol($infos->get('offset'));

        $this->worksheet->getCellByColumnAndRow($col, $row)->setValue($node->getLabel());
    }
}