<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 04/04/2017
 * Time: 21:48
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Visitor;


use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use ToolsBundle\Services\ExcelMappingParser\ExcelManifest;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AbstractComponent;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AbstractNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\CollectionNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\EntityNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\PropertyLeaf;
use ToolsBundle\Services\ExcelNodeVisitor\Node\RootNode;

/**
 * Class ExcelScalarExportVisitor
 * @package ToolsBundle\Services\ExcelNodeVisitor\Visitor
 */
class ExcelScalarExporterVisitor extends AbstractExcelExporterVisitor
{
    /**
     * @var int
     */
    private $col;
    /**
     * @var int
     */
    private $row;

    /**
     * @var array
     */
    private $result;

    /**
     * @var PropertyAccessor
     */
    private $accessor;

    /**
     * ExcelScalarExportVisitor constructor.
     * @param ExcelManifest $manifest
     */
    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    public function exportExcelTable(Query $query, RootNode $rootNode, ParameterBag $nodeInfo) {
        $this->row = $rootNode->getMaxDepth();
        //dump("Query requests: " . $query->getDQL());
        //dump("Query results: " . count($query->getScalarResult()));
        foreach($query->getScalarResult() as $result) {
            $this->result = $result;
            $this->row++;
            $this->col = $nodeInfo->get('offset');
            $rootNode->accept($this);
        }
    }

    public function visitRootNode(RootNode $node)
    {
        $this->handleComponentNode($node);
    }

    public function visitCollectionNode(CollectionNode $node)
    {
        $this->handleComponentNode($node);
    }

    public function visitEntityNode(EntityNode $node)
    {
        $this->handleComponentNode($node);
    }

    public function visitPropertyLeaf(PropertyLeaf $node)
    {
        $accessKey = "[" . $node->getParent()->getIdentifier() . "_" . $node->getProperty() . "]";

        if($this->accessor->isReadable($this->result, $accessKey))
            $this->getWorksheet()->setCellValueByColumnAndRow($this->col, $this->row, $this->accessor->getValue($this->result, $accessKey));
        else {
            //This should be true if this part is executed
            assert(!array_key_exists($accessKey, $this->result));
        }

        if(!$this->getWorksheet()->getColumnDimensionByColumn($this->col)->getAutoSize())
            $this->getWorksheet()->getColumnDimensionByColumn($this->col++)->setAutoSize(true);
        else
            $this->col++;
    }

    private function handleComponentNode(AbstractComponent $node) {
        $node->getProperties()->forAll(function($key, AbstractNode $childNoe) {
            $childNoe->accept($this);
            return true;
        });
    }
}