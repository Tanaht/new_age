<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 17/04/2017
 * Time: 14:25
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Visitor;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\ParameterBag;
use ToolsBundle\Services\ErrorMessagesTrait;
use ToolsBundle\Services\ExcelNodeVisitor\Node\CollectionNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\EntityNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\PropertyLeaf;
use ToolsBundle\Services\ExcelNodeVisitor\Node\RootNode;

class ExcelEntityImporterVisitor extends AbstractExcelImporterVisitor
{
    use ErrorMessagesTrait;

    /**
     * @var ParameterBag
     */
    private $foundEntities;
    /**
     * @var ArrayCollection
     */
    private $imports;

    /**
     * TODO: Returned the collection of all imported objects may froze the server (too many memory requested) So, the importation may have to be imported with a lazy policy (import objects by step of 50 objects for example, this way we can clean the unused memory).
     * @param RootNode $rootNode
     * @param ParameterBag $nodeInfo
     * @return Collection
     */
    public function importExcelTable(RootNode $rootNode, ParameterBag $nodeInfo)
    {
        $this->foundEntities = new ParameterBag();
        $this->imports = new ArrayCollection();


        return $this->imports;
    }

    public function visitRootNode(RootNode $node)
    {
        switch($node->getImportOptions()->get('action')) {
            case 'owner':
                break;
            case 'insert':
                break;
        }
    }

    public function visitCollectionNode(CollectionNode $node)
    {
        switch($node->getImportOptions()->get('action')) {
            case 'filter':
                break;
            case 'link':
                break;
            case 'insert':
                break;
        }
    }

    public function visitEntityNode(EntityNode $node)
    {
        switch($node->getImportOptions()->get('action')) {
            case 'filter':
                break;
            case 'link':
                break;
            case 'insert':
                break;
        }
    }

    public function visitPropertyLeaf(PropertyLeaf $node)
    {
        if($node->getParent()->getImportOptions()->get('action') !== 'insert') {
            return;
        }
    }
}