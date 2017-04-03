<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 02/04/2017
 * Time: 15:13
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Visitor;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use ToolsBundle\Services\ExcelMappingParser\ExcelManifest;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AttributeNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\CollectionNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\EntityNode;
use PHPExcel_Worksheet;

class ExcelEntityHydratorNodeVisitor extends AbstractNodeVisitor
{

    /**
     * @var ExcelManifest
     */
    private $manifest;
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ArrayCollection
     */
    private $results;

    /**
     * Doctrine current Doctrine Object used to hydrate excel file
     * @var object
     */
    private $current;

    /**
     * This bag contains an array of (row counter and Entity Object for each Collection Node)
     * @var ParameterBag
     */
    private $collectionsInfos;

    /**
     * The excel worksheet associated with this
     * @var PHPExcel_Worksheet
     */
    private $worksheet;

    /**
     * @var PropertyAccessor
     */
    private $accessor;
    /**
     * QueryBuilderNodeVisitor constructor.
     * @param ExcelManifest $manifest
     */
    public function __construct(ExcelManifest $manifest, EntityManager $em, Query $query)
    {
        $this->results = new ArrayCollection($query->getResult());
        $this->manifest = $manifest;
        $this->em = $em;
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->collectionsInfos = new ParameterBag();
    }

    public function hydrateExcelFile(PHPExcel_Worksheet $worksheet, EntityNode $node) {
        $entityInfos = $this->manifest->getEntityInfos($node->getIdentifier());

        if($node->hasParent() || $entityInfos->has('reference'))
            throw new LogicException("Cannot Hydrate entities from a child nodes, or event a referenced Root Node");

        if($worksheet->getTitle()  !== $entityInfos->get('sheet')) {
            throw new LogicException("The Given Excel WorkSheet does't follow the entity specs !!!");
        }

        $this->worksheet = $worksheet;
        foreach ($this->results as $result) {
            $this->current = $result;
            $node->accept($this);
        }
    }



    public function visitEntityNode(EntityNode $node)
    {
        foreach ($node->getChildrens() as $childNode) {
            $childNode->accept($this);
        }
    }

    public function visitAttributeNode(AttributeNode $node)
    {
        $rowOffset = 0;
        $colOffset = $this->manifest->getEntityInfos($node->getRootNode()->getIdentifier())->get('offset');
        $current = null;
        $results = null;
        if($this->collectionsInfos->has($node->getRootNode()->getIdentifier())) {
            $current = $this->collectionsInfos->get($node->getRootNode()->getIdentifier())->get('current');
            $results = $this->collectionsInfos->get($node->getRootNode()->getIdentifier())->get('results');
            $rowOffset = $this->collectionsInfos->get($node->getRootNode()->getIdentifier())->get('offset');
        }
        else {
            $current = $this->current;
            $results = $this->results;
        }

        if($this->accessor->isReadable($current, $node->getProperty())) {
            $value = $this->accessor->getValue($current, $node->getProperty());
            $col = $node->getCol($colOffset);
            $row = $rowOffset + $node->getDepth() + $results->indexOf($current) + 1;
            $this->worksheet->getCellByColumnAndRow($col, $row)->setValue($value);
        }
        else {
            dump("Not Readable for: ", $node->getProperty());
        }
    }

    public function visitCollectionNode(CollectionNode $node)
    {
        if($this->accessor->isReadable($this->current, $node->getProperty())) {
            $collection = $this->accessor->getValue($this->current, $node->getProperty());

            if(!$this->collectionsInfos->has($node->getReference())) {
                $this->collectionsInfos->set($node->getReference(), new ParameterBag(['results' => $collection, 'offset' => 0]));
            }
            else {
                $this->collectionsInfos->get($node->getReference())->set('results', $collection);
            }

            foreach ($collection as $result) {
                $this->collectionsInfos->get($node->getReference())->set('current', $result);
                $node->getReferencedNode()->accept($this);
            }

            $this->collectionsInfos->get($node->getReference())->set('offset', $this->collectionsInfos->get($node->getReference())->get('offset') + $collection->count());
        }
    }
}