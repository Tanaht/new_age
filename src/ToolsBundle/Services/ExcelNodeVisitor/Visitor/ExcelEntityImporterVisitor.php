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
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use JMS\Serializer\Exception\LogicException;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use ToolsBundle\Services\ErrorMessagesTrait;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AbstractComponent;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AbstractLeaf;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AbstractNode;
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
    private $storedEntities;

    /**
     * @var ParameterBag
     */
    private $currentEntitiesInstanciated;

    /**
     * @var ArrayCollection
     */
    private $imports;

    /**
     * @var PropertyAccessor
     */
    private $pa;

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var ExcelEntityFinderVisitor
     */
    private $entityFinder;

    /**
     * @var int
     */
    private $row;

    function __construct(EntityManager $manager)
    {
        $this->pa = PropertyAccess::createPropertyAccessor();
        $this->manager = $manager;
        $this->entityFinder = new ExcelEntityFinderVisitor($manager);
    }


    /**
     * TODO: http://www.doctrine-project.org/2009/08/07/doctrine2-batch-processing.html
     * TODO: http://doctrine-orm.readthedocs.io/en/latest/reference/batch-processing.html
     * TODO: Returned the collection of all imported objects may froze the server (too many memory requested) So, the importation may have to be imported with a lazy policy (import objects by step of 50 objects for example, this way we can clean the unused memory).
     * @param RootNode $rootNode
     * @param ParameterBag $nodeInfo
     * @return Collection
     */
    public function importExcelTable(RootNode $rootNode, ParameterBag $nodeInfo)
    {
        $this->entityFinder->setWorksheet($this->getWorksheet());
        $this->storedEntities = new ParameterBag();
        $this->currentEntitiesInstanciated = new ParameterBag();
        $this->imports = new ArrayCollection();

        $rowIterator = $this->getWorksheet()->getRowIterator($rootNode->getMaxDepth() + 1, $this->getWorksheet()->getHighestRow());

        foreach ($rowIterator as $row) {
            $this->row = $row->getRowIndex();

            try {
                $rootNode->accept($this);
            }
            catch(EntityNotFoundException $e) {
                $this->addError($e->getMessage());
                break;
            }
            catch(LogicException $e) {
                $this->addError($e->getMessage());
                break;
            }
        }

        foreach ($this->storedEntities->get($rootNode->getIdentifier())->getIterator() as $entityToImport) {
            $this->imports->add($entityToImport);
        }
        return $this->imports;
    }

    public function visitRootNode(RootNode $node)
    {
        switch($node->getImportOptions()->get('action')) {
            case 'owner':
                $this->findEntity($node);
                break;
            case 'insert':
                $idNode = $this->getIdNode($node);
                $this->storeValue($node, $this->getExcelValue($idNode->getCol()), $node->getMetadata()->getReflectionClass()->newInstance());
                break;
        }

        foreach ($node->getProperties()->getIterator() as $childNode) {
            /** @var AbstractLeaf $childNode */
            $childNode->accept($this);
        }
    }

    public function visitCollectionNode(CollectionNode $node)
    {
        switch($node->getImportOptions()->get('action')) {
            case 'filter':
                //Never Triggered (the filter node are visited with an extra visitor (ExcelEntityFinderVisitor)
                break;
            case 'link':
                $links = $this->findEntities($node);
                $propertyPath = $node->getProperty();
                $parent = $this->retrieveEntity($node->getParent());

                if($this->pa->isWritable($parent, $propertyPath) && count($this->pa->getValue($parent, $propertyPath)) === 0) {//Check if 0 to avoid multiple add.
                    //TODO: be sure that addXXX method is call normally it is

//                    foreach ($links as $link) {
//                        dump($parent->getName() . " => " . $node->getProperty() . " => " . $link->getId() . " => " . $link->getName());
//                    }
                    $this->pa->setValue($parent, $propertyPath, $links);
                }

                //End of treatment (the filter node childs are visited with an extra visitor (ExcelEntityFinderVisitor)
                break;
            case 'insert':
                $idNode = $this->getIdNode($node);
                $this->storeValue($node, $this->getExcelValue($idNode->getCol()), $node->getMetadata()->getReflectionClass()->newInstance());

                foreach ($node->getProperties()->getIterator() as $childNode) {
                    /** @var AbstractLeaf $childNode */
                    $childNode->accept($this);
                }

                $object = $this->retrieveEntity($node);
                $propertyPath = $node->getProperty();
                $parent = $this->retrieveEntity($node->getParent());

                if($this->pa->isWritable($parent, $propertyPath)) {

                    $array = $this->pa->getValue($parent, $propertyPath)->toArray();

                    if(false === array_search($object, $array)) {
                        $array[] = $object;
                        //TODO: Check that all entity in collection are properly added (with method addXXX Not sure) for correct binding
                        $this->pa->setValue($parent, $propertyPath, $array);

                        //if($node->getRootNode() === $node->getParent()) {
                        //    dump($object->getName());
                        //}
                    }
                }

                break;
        }
    }

    public function visitEntityNode(EntityNode $node)
    {
        switch($node->getImportOptions()->get('action')) {
            case 'filter':
                //Never Triggered (the filter node are visited with an extra visitor (ExcelEntityFinderVisitor)
                break;
            case 'link':
                $link = $this->findEntity($node);

                $propertyPath = $node->getProperty();
                $parent = $this->retrieveEntity($node->getParent());

                if($this->pa->isWritable($parent, $propertyPath)) {
                    $this->pa->setValue($parent, $propertyPath, $link);
                }

                //End of treatment (the filter node childs are visited with an extra visitor (ExcelEntityFinderVisitor)
                break;
            case 'insert':
                $idNode = $this->getIdNode($node->getParent());
                $this->storeValue($node, $this->getExcelValue($idNode->getCol()), $node->getMetadata()->getReflectionClass()->newInstance());

                foreach ($node->getProperties()->getIterator() as $childNode) {
                    /** @var AbstractLeaf $childNode */
                    $childNode->accept($this);
                }

                $object = $this->retrieveEntity($node);
                $propertyPath = $node->getProperty();
                $parent = $this->retrieveEntity($node->getParent());

                if($this->pa->isWritable($parent, $propertyPath)) {
                    $this->pa->setValue($parent, $propertyPath, $object);
                }
                break;
        }
    }

    public function visitPropertyLeaf(PropertyLeaf $node)
    {
        if($node->getParent()->getImportOptions()->get('action') !== 'insert' || $node->getProperty() === 'id') {
            return;
        }

        //get the related mapping of this node to know is type and if it is nullable
        $fieldMapping = $node->getParent()->getMetadata()->getFieldMapping($node->getProperty());

        $propertyPath = $node->getProperty();
        $parent = $this->retrieveEntity($node->getParent());
        $value = $this->getExcelValue($node->getCol());


        if($value === null) {
            if($fieldMapping['nullable']) {
                if($this->pa->isWritable($parent, $propertyPath)) {
                    $this->pa->setValue($parent, $propertyPath, null);
                }
            }
            elseif ($fieldMapping['type'] === Type::STRING || $fieldMapping['type'] === Type::TEXT) {
                if($this->pa->isWritable($parent, $propertyPath)) {
                    $this->pa->setValue($parent, $propertyPath, '');
                }
            }
        }
        else {
            if($this->pa->isWritable($parent, $propertyPath)) {
                $this->pa->setValue($parent, $propertyPath, $value);
            }
        }
    }

    /**
     * Get Id Node of the component in parameter
     * @param AbstractComponent $component
     * @return PropertyLeaf
     */
    private function getIdNode(AbstractComponent $component) {

        return $component->getProperties()->filter(function(AbstractNode $child) {
            /** @var PropertyLeaf $child */
            return $child->getManifest()->get('type') === AbstractNode::PROPERTY && $child->getProperty() === 'id';
        })[0];
    }

    /**
     * @param $col integer
     * @return mixed
     */
    private function getExcelValue($col) {
        return $this->getWorksheet()->getCellByColumnAndRow($col, $this->row)->getValue();
    }

    /**
     * @param CollectionNode $component
     * @return ArrayCollection
     */
    private function findEntities(CollectionNode $component) {
        $idNode = $this->getIdNode($component->getParent());
        $id = $this->getExcelValue($idNode->getCol());
        $currentRow = $this->row;

        $entities = new ArrayCollection();
        while($id === $this->getExcelValue($idNode->getCol())) {
            $entity = $this->findEntity($component);

            if(!$entities->contains($entity))
                $entities->add($this->findEntity($component));
            $this->row++;
        }

        $this->row = $currentRow;
        return $entities;
    }

    /**
     * @param AbstractComponent $component
     * @return object
     */
    private function retrieveEntity(AbstractComponent $component) {
        return $this->storedEntities->get($component->getIdentifier())->get($this->currentEntitiesInstanciated->get($component->getIdentifier()));
    }

    /**
     * @param AbstractComponent $component
     * @param int $id
     * @param array|object $value
     * @throws LogicException
     * @return void
     */
    private function storeValue(AbstractComponent $component, $id, $value) {
        if(is_float($id))
            $id = intval($id);

        if(!(is_int($id) || is_string($id))) {
            throw new LogicException("An Integer identifier was expected, given: " . $id);
        }

        if(!$this->storedEntities->has($component->getIdentifier())) {
            $this->storedEntities->set($component->getIdentifier(), new ParameterBag([$id => $value]));
            $this->currentEntitiesInstanciated->set($component->getIdentifier(), $id);
        }

        if(!$this->storedEntities->get($component->getIdentifier())->has($id)) {
            $this->storedEntities->get($component->getIdentifier())->set($id, $value);
            $this->currentEntitiesInstanciated->set($component->getIdentifier(), $id);
        }
    }

    /**
     * @param AbstractComponent $component
     * @return object
     */
    private function findEntity(AbstractComponent $component) {
        $idNode = $this->getIdNode($component);
        $id = $this->getExcelValue($idNode->getCol());

        if(is_float($id))
            $id = intval($id);

        if(!(is_int($id) || is_string($id))) {
            throw new LogicException("An Integer identifier was expected, given: " . $id);
        }

        $this->storeValue($component, $id, $this->entityFinder->findEntity($this->row, $component));

        return $this->storedEntities->get($component->getIdentifier())->get($id);
    }
}