<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 06/04/2017
 * Time: 13:46
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Visitor;


use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AbstractComponent;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AbstractNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\CollectionNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\EntityNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\PropertyLeaf;
use ToolsBundle\Services\ExcelNodeVisitor\Node\RootNode;
use PHPExcel_Style_Alignment;

class ExcelArrayExporterVisitor extends AbstractExcelExporterVisitor
{

    /**
     * @var ParameterBag
     */
    private $resultBag;

    /**
     * @var PropertyAccessor
     */
    private $accessor;

    /**
     * @var int
     */
    private $row;

    /**
     * @var int
     */
    private $columnOffset;

    function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->resultBag = new ParameterBag();
    }

    public function exportExcelTable(Query $query, RootNode $rootNode, ParameterBag $nodeInfo)
    {
        $this->columnOffset = $nodeInfo->get('offset');
        $this->row = $rootNode->getMaxDepth() + 1;
        $results = $query->getArrayResult();

        foreach ($results as $result) {
            $startRow = $this->row;
            $this->resultBag->set($rootNode->getIdentifier(), $result);
            $rootNode->accept($this);
            $this->mergeSubNodes($startRow, $rootNode);
            $this->row++;
        }
        $this->row--;


        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            )
        );

        $this->getWorksheet()->getStyleByColumnAndRow($this->columnOffset, 1, $this->columnOffset + $rootNode->getWidth(), $this->row)->applyFromArray($style);

        for($i = $this->columnOffset ; $i < $this->columnOffset + $rootNode->getWidth() ; $i++) {
            $this->getWorksheet()->getColumnDimensionByColumn($i)->setAutoSize(true);
        }
    }

    public function visitRootNode(RootNode $node)
    {

        $node->getProperties()->forAll(function($key, AbstractNode $child) {
            $child->accept($this);
            return true;
        });
    }

    public function visitCollectionNode(CollectionNode $node)
    {

        $propertyHolder = $this->resultBag->get($node->getParent()->getIdentifier());

        $results = $this->getValue($propertyHolder, $node->getProperty());
        foreach ($results as $result) {
            $startRow = $this->row;
            $this->resultBag->set($node->getIdentifier(), $result);

            $node->getProperties()->forAll(function($key, AbstractNode $child) use($startRow) {
                $child->accept($this);
                return true;
            });
            $this->mergeSubNodes($startRow, $node);
            $this->row++;
        }
        $this->row--;
    }

    public function visitEntityNode(EntityNode $node)
    {
        $propertyHolder = $this->resultBag->get($node->getParent()->getIdentifier());
        $result = $this->getValue($propertyHolder, $node->getProperty());

        $this->resultBag->set($node->getIdentifier(), $result);

        $node->getProperties()->forAll(function($key, AbstractNode $child) {
            $child->accept($this);
            return true;
        });
    }

    public function visitPropertyLeaf(PropertyLeaf $node)
    {
        $propertyHolder = $this->resultBag->get($node->getParent()->getIdentifier());
        $value = $this->getValue($propertyHolder, $node->getProperty());

        $this->getWorksheet()->getCellByColumnAndRow($node->getCol() + $this->columnOffset, $this->row)->setValue($value);
    }

    /**
     * @param $holder
     * @param $property
     * @return mixed
     * @throws AccessException if something happened when trying to getValue
     */
    private function getValue($holder, $property) {
        if(!is_array($holder) && !is_object($holder)) {
            throw new AccessException("Cannot get property of something that is not either an object or an array: " . $holder);
        }

        if(is_object($holder)) {
            if($this->accessor->isReadable($holder, $property)) {
                return $this->accessor->getValue($holder, $property);
            }
            else {
                throw new AccessException("The underlying object is unreadable");
            }
        }
        else {
            if($this->accessor->isReadable($holder, '[' . $property . ']')) {
                return $this->accessor->getValue($holder, '[' . $property . ']');
            }
            else {
                throw new AccessException("The underlying object is unreadable");
            }
        }
    }

    private function mergeSubNodes($startRow, AbstractComponent $node) {
        $leafFiltered = $node->getProperties()->filter(function(AbstractNode $child) {
            return $child->getManifest()->get('type') === AbstractNode::PROPERTY;
        });

        $leafFiltered->forAll(function($key, PropertyLeaf $leaf) use($startRow) {
            $start = $this->getWorksheet()->getCellByColumnAndRow($leaf->getCol() + $this->columnOffset, $startRow)->getCoordinate();
            $end = $this->getWorksheet()->getCellByColumnAndRow($leaf->getCol() + $this->columnOffset, $this->row)->getCoordinate();

            if($startRow < $this->row) {
                $this->getWorksheet()->mergeCells($start . ":" . $end);
            }
            return true;
        });

        $entityFiltered = $node->getProperties()->filter(function(AbstractNode $child) {
            return $child->getManifest()->get('type') === AbstractNode::ENTITY;
        });

        $entityFiltered->forAll(function($key, EntityNode $leaf) use($startRow) {
            $this->mergeSubNodes($startRow, $leaf);
            return true;
        });
    }
}