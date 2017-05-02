<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 04/04/2017
 * Time: 20:09
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Visitor;


use Symfony\Component\HttpFoundation\ParameterBag;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AbstractComponent;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AbstractNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\CollectionNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\EntityNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\PropertyLeaf;
use ToolsBundle\Services\ExcelNodeVisitor\Node\RootNode;
use PHPExcel_Worksheet;
use PHPExcel_Style_Border;

class HeaderBuilderVisitor extends AbstractNodeVisitor
{
    /**
     * @var integer
     */
    private $col;
    /**
     * @var PHPExcel_Worksheet
     */
    private $worksheet;


    public function __construct()
    {
        $this->worksheet = null;
    }

    /**
     * @param PHPExcel_Worksheet $worksheet
     */
    public function setWorksheet($worksheet)
    {
        $this->worksheet = $worksheet;
    }



    public function generateHeader(RootNode $rootNode, ParameterBag $info) {
        $this->col = $info->get('offset');

        $rootNode->accept($this);

        //Style Settings:
        $this->worksheet
            ->getStyleByColumnAndRow($info->get('offset'), 0, $this->col - 1, $rootNode->getMaxDepth())
            ->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM)
        ;
    }


    public function visitRootNode(RootNode $node)
    {
        //dump($node . " Col: " . $this->col . " width: " . $node->getWidth() . " row: " . $node->getDepth());

        $this->handleComponentNode($node);
        $node->getProperties()->forAll(function($key, AbstractNode $childNode) {
            $childNode->accept($this);
            return true;
        });
    }

    public function visitCollectionNode(CollectionNode $node)
    {
        //dump($node . " Col: " . $this->col . " width: " . $node->getWidth() . " row: " . $node->getDepth());
        $this->handleComponentNode($node);

        $node->getProperties()->forAll(function($key, AbstractNode $childNode) {
            $childNode->accept($this);
            return true;
        });
    }

    public function visitEntityNode(EntityNode $node)
    {
        //dump($node . " Col: " . $this->col . " width: " . $node->getWidth() . " row: " . $node->getDepth());
        $this->handleComponentNode($node);
        $node->getProperties()->forAll(function($key, AbstractNode $childNode) {
            $childNode->accept($this);
            return true;
        });
    }

    public function visitPropertyLeaf(PropertyLeaf $node)
    {
        //dump($node . " Col: " . $this->col . " row: " . $node->getDepth());

        $col = $this->col;
        $node->setCol($col);
        $row = $node->getDepth();
        $this->worksheet->getCellByColumnAndRow($col, $row)->setValue($node->getLabel());

        $this->col++;
    }

    private function handleComponentNode(AbstractComponent $node) {
        $startCol = $this->col;
        $endCol = $this->col + $node->getWidth() - 1;

        $row = $node->getDepth();

        $startCell = $this->worksheet->getCellByColumnAndRow($startCol, $row)->getCoordinate();
        $endCell = $this->worksheet->getCellByColumnAndRow($endCol, $row)->getCoordinate();

        $this->worksheet->mergeCells($startCell . ':' . $endCell);

        $this->worksheet->getCell($startCell)->setValue($node->getLabel());
    }
}