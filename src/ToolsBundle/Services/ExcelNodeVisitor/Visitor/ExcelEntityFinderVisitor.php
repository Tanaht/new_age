<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 16/04/2017
 * Time: 16:45
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Visitor;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Internal\Hydration\AbstractHydrator;
use Doctrine\ORM\QueryBuilder;
use JMS\Serializer\Exception\LogicException;
use Psr\Log\InvalidArgumentException;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use ToolsBundle\Services\ErrorMessagesTrait;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AbstractComponent;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AbstractNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\CollectionNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\EntityNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\PropertyLeaf;
use ToolsBundle\Services\ExcelNodeVisitor\Node\RootNode;

class ExcelEntityFinderVisitor extends AbstractExcelVisitor
{
    use ErrorMessagesTrait;
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var ParameterBag
     */
    private $queryParameters;

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var AbstractComponent
     */
    private $node;

    /**
     * @var int
     */
    private $row;

    /**
     * @var bool
     */
    private $isSubrequest;


    public function __construct(EntityManager $manager, $subrequest = false)
    {
        $this->manager = $manager;
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->isSubrequest = $subrequest;
    }


    /**
     * @param $row
     * @param CollectionNode $collection
     * @return ArrayCollection
     * @throws EntityNotFoundException|LogicException
     */
    public function findEntities($row, CollectionNode $collection) {
        if($collection->getImportOptions()->get('action') !== 'link') {
            throw new LogicException("Cannot get entities of a Node that has not the import options : action = 'link' ");
        }

        $this->row = $row;

        /** @var PropertyLeaf $idNode */
        $idNode = $collection->getParent()->getProperties()->filter(function(AbstractNode $child) {
            /** @var PropertyLeaf $child */
            return $child->getManifest()->get('type') === AbstractNode::PROPERTY && $child->getProperty() === 'id';
        })[0];
        $currentRow = $this->row;
        $currentId = $this->getWorksheet()->getCellByColumnAndRow($idNode->getCol(), $this->row)->getValue();


        $results = new ArrayCollection();
        while($currentId === $this->getWorksheet()->getCellByColumnAndRow($idNode->getCol(), $this->row)->getValue()) {
            $entity = $this->findEntity($row++, $collection);

            if(!$results->contains($entity))
                $results->add($entity);
        }

        return $results;
    }
    /**
     * @param integer $row
     * @param AbstractComponent $component
     * @param ParameterBag $componentInfos
     * @return array|object
     * @throws EntityNotFoundException if there is not exactly one result
     */
    public function findEntity($row, AbstractComponent $component) {
        if($this->hasErrors())
            $this->clearErrors();

        $this->queryParameters = new ParameterBag();
        $this->queryBuilder = $this->manager->getRepository($component->getMetadata()->getName())->createQueryBuilder($component->getIdentifier());

        $this->node = $component;
        $this->row = $row;

        foreach ($component->getProperties()->getIterator() as $childNode) {
            /** @var AbstractNode $childNode */
            if($childNode->getManifest()->get('type') === AbstractNode::PROPERTY || $childNode->getImportOptions()->get('action') === 'filter') {
                $childNode->accept($this);
            }
        }

        $query = $this->queryBuilder->getQuery();

        $query->setParameters($this->queryParameters->all());

        //dump($query->getDQL());
        $result = $query->getResult();

        if($result === null) {
            $this->addError("Unable to find value in database for Field named '" . $component->getLabel() . "' at row " . $this->row );
            throw new EntityNotFoundException("Unable to find value in database for Field named '" . $component->getLabel() . "' at row " . $this->row );
        }
        elseif (count($result) === 0) {
            $this->addError("Unable to find value in database for Field named '" . $component->getLabel() . "' at row " . $this->row );
            throw new EntityNotFoundException("Unable to find value in database for Field named '" . $component->getLabel() . "' at row " . $this->row );
        }
        elseif($this->isSubrequest === false && count($result) > 1) {
            $this->addError("Too many value find in database for Field named '" . $component->getLabel() . "' at row " . $this->row );
            throw new EntityNotFoundException("Too many value find in database for Field named '" . $component->getLabel() . "' at row " . $this->row );
        }
        else {
            if($this->isSubrequest === false)
                return $result[0];
            else
                return $result;
        }

    }


    public function visitRootNode(RootNode $node)
    {
        // This visitor is never called, the method findEntity is trigger for the root node or link node.
    }

    public function visitCollectionNode(CollectionNode $node)
    {
        if($node->getImportOptions()->get('action') !== 'filter')
            return;

        $join = $node->getParent()->getIdentifier() . "." . $node->getProperty();
        $alias = $node->getIdentifier();
        $this->queryBuilder->innerJoin($join, $alias)->addSelect($alias);

        /** @var PropertyLeaf $idNode */
        $idNode = $node->getParent()->getProperties()->filter(function(AbstractNode $child) {
            /** @var PropertyLeaf $child */
            return $child->getManifest()->get('type') === AbstractNode::PROPERTY && $child->getProperty() === 'id';
        })[0];
        $currentRow = $this->row;
        $currentId = $this->getWorksheet()->getCellByColumnAndRow($idNode->getCol(), $currentRow)->getValue();

        $results = new ArrayCollection();
        while($currentId === $this->getWorksheet()->getCellByColumnAndRow($idNode->getCol(), $this->row)->getValue()) {
            $entitiesResults = $this->createRelatedEntityFinder()->findEntity($this->row, $node);
            foreach ($entitiesResults as $entityResult) {
                if(!$results->contains($entityResult->getId()))
                    $results->add($entityResult->getId());
            }
            //dump("compute query for row " . $this->row . " for column: " . $node->getLabel());
            $this->row++;
        }
        $this->row = $currentRow;

        $this->queryBuilder->andWhere($this->queryBuilder->expr()->in($alias . '.id', $results->toArray()));
    }

    public function visitEntityNode(EntityNode $node)
    {
        if($node->getImportOptions()->get('action') !== 'filter')
            return;


        $join = $node->getParent()->getIdentifier() . "." . $node->getProperty();
        $alias = $node->getIdentifier();
        $this->queryBuilder->innerJoin($join, $alias)->addSelect($alias);


        $entitiesResults = $this->createRelatedEntityFinder()->findEntity($this->row, $node);
        $results = new ArrayCollection();
        foreach ($entitiesResults as $entityResult) {
            if(!$results->contains($entityResult->getId()))
                $results->add($entityResult->getId());
        }

        $this->queryBuilder->andWhere($this->queryBuilder->expr()->in($alias . ".id", $results->toArray()));
    }

    public function visitPropertyLeaf(PropertyLeaf $node)
    {
        if($node->getProperty() === 'id' && $node->getParent()->getImportOptions()->get('id') !== 'real') {
            return;
        }

        //get the related mapping of this node to know is type and if it is nullable
        $fieldMapping = $node->getParent()->getMetadata()->getFieldMapping($node->getProperty());

        $value = $this->getWorksheet()->getCellByColumnAndRow($node->getCol(), $this->row)->getValue();


        $expr = null;
        if($value === null) {
            if($fieldMapping['nullable']) {
                $expr = $this->queryBuilder->expr()->isNull($node->getParent()->getIdentifier() . '.' . $node->getProperty());
            }
            elseif ($fieldMapping['type'] === Type::STRING || $fieldMapping['type'] === Type::TEXT) {
                $expr = $this->queryBuilder->expr()->eq($node->getParent()->getIdentifier() . '.' . $node->getProperty(), "");
            }
        }
        else {
            $expr = $this->queryBuilder->expr()->eq($node->getParent()->getIdentifier() . '.' . $node->getProperty(), $this->getParameterKey($value));
        }

        $this->queryBuilder->andWhere($expr);
    }


    /**
     * @param string|integer|boolean $value
     * @return string Key of the given value
     */
    private function getParameterKey($value) {
        $key = ":key_" . $this->queryParameters->count();
        $this->queryParameters->set($key, $value);
        return $key;
    }


    /**
     * @return ExcelEntityFinderVisitor
     */
    private function createRelatedEntityFinder() {
        $entityFinder = new ExcelEntityFinderVisitor($this->manager, true);
        $entityFinder->setWorksheet($this->getWorksheet());
        return $entityFinder;
    }
}