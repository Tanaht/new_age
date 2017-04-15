<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 05/04/2017
 * Time: 11:29
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Visitor;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Validator\ValidatorBuilder;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AbstractComponent;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AbstractNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\CollectionNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\EntityNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\PropertyLeaf;
use ToolsBundle\Services\ExcelNodeVisitor\Node\RootNode;
use PHPExcel_Worksheet;

class HeaderValidatorVisitor extends AbstractExcelVisitor
{
    /**
     * @var integer
     */
    private $col;

    /***
     * @var boolean
     */
    private $isValid;

    /**
     * @var array
     */
    private $errors;

    /**
     * @param RootNode $rootNode
     * @param ParameterBag $info
     * @return boolean if Excel Header correspond to the manifest
     */
    public function validateHeader(RootNode $rootNode, ParameterBag $info) {
        $this->col = $info->get('offset');
        $this->errors = [];
        $this->isValid = true;
        $rootNode->accept($this);
        return $this->isValid;
    }


    public function visitRootNode(RootNode $node)
    {
        $this->handleComponentNode($node);

        $node->getProperties()->forAll(function($key, AbstractNode $childNode) {
            $childNode->accept($this);
            return true;
        });
    }

    public function visitCollectionNode(CollectionNode $node)
    {
        $this->handleComponentNode($node);

        $node->getProperties()->forAll(function($key, AbstractNode $childNode) {
            $childNode->accept($this);
            return true;
        });
    }

    public function visitEntityNode(EntityNode $node)
    {
        $this->handleComponentNode($node);
        $node->getProperties()->forAll(function($key, AbstractNode $childNode) {
            $childNode->accept($this);
            return true;
        });
    }

    public function visitPropertyLeaf(PropertyLeaf $node)
    {

        $col = $this->col;
        $row = $node->getDepth();
        $cellCoordinate = $this->getWorksheet()->getCellByColumnAndRow($col, $row)->getCoordinate();
        if($this->getWorksheet()->getCell($cellCoordinate)->getValue() !== $node->getLabel()) {
            $this->errors[] = "Sur la feuille: '" . $this->getWorksheet()->getTitle() . "' la valeur sur la cellule '" . $cellCoordinate . "' devrait être égale à '" . $node->getLabel() . "'";
            $this->isValid = false;
        }

        $node->setCol($col);
        $this->col++;
    }

    private function handleComponentNode(AbstractComponent $node) {
        $startCol = $this->col;
        $endCol = $this->col + $node->getWidth() - 1;

        $row = $node->getDepth();

        $startCoordinate = $this->getWorksheet()->getCellByColumnAndRow($startCol, $row)->getCoordinate();
        $endCoordinate = $this->getWorksheet()->getCellByColumnAndRow($endCol, $row)->getCoordinate();

        $mergeCells = new ArrayCollection($this->getWorksheet()->getMergeCells());

        if(!$mergeCells->contains($startCoordinate . ':' . $endCoordinate)) {
            $this->errors[] = "Sur la feuille: " . $this->getWorksheet()->getTitle() . " la plage de données [" . $startCoordinate . ':' . $endCoordinate . "] devrait être fusionné";
            $this->isValid = false;
            return;
        }


        if($this->getWorksheet()->getCell($startCoordinate)->getValue() !== $node->getLabel()) {
            $this->errors[] = "Sur la feuille: '" . $this->getWorksheet()->getTitle() . "' la valeur sur la cellule " . $startCoordinate . " devrait être égale à '" . $node->getLabel() ."'";
            $this->isValid = false;
            return;
        }
    }

    /**
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }
}