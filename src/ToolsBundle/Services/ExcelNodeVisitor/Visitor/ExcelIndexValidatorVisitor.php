<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 15/04/2017
 * Time: 17:06
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Visitor;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AbstractNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\CollectionNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\EntityNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\PropertyLeaf;
use ToolsBundle\Services\ExcelNodeVisitor\Node\RootNode;
use PHPExcel_Worksheet_Row;


class ExcelIndexValidatorVisitor extends AbstractExcelVisitor
{
    /**
     * @var PHPExcel_Worksheet_Row
     */
    private $row;

    /**
     * @var string
     */
    private $currentEntityIndex;

    /**
     * Cette collection permet de vérifier l'unicité des lignes qui vont être importé.
     * @var ParameterBag
     */
    private $uniqIndexCollections;

    /**
     * Cette collection permet de vérifier qu'il n'y a qu'une seule entité importer dans un noeud Entity.
     * Cela n'a pas pour but de valider, juste de permettre de relever certaines erreurs courantes.
     * @var ParameterBag
     */
    private $uniqEntityCollections;

    /**
     * @var Collection;
     */
    private $errors;

    public function __construct()
    {
        $this->uniqIndexCollections = new ParameterBag();
        $this->uniqEntityCollections = new ParameterBag();
        $this->errors = new ArrayCollection();
    }

    /**
     * Valide l'indexation d'un fichier d'importation excel
     * @param RootNode $node
     * @param ParameterBag $nodeInfos
     * @return bool
     */
    public function validate(RootNode $node, ParameterBag $nodeInfos) {
        $this->errors->clear();
        $this->uniqIndexCollections = new ParameterBag();
        $this->uniqEntityCollections = new ParameterBag();

        //dump("Cols Margin: " . $nodeInfos->get('offset') . '-' .  $node->getWidth(), "Rows Margin: " . ($node->getMaxDepth() + 1) . '-' . $this->getWorksheet()->getHighestRow());

        $rowIterator = $this->getWorksheet()->getRowIterator($node->getMaxDepth() + 1, $this->getWorksheet()->getHighestRow());

        foreach ($rowIterator as $row) {
            $this->row = $row;
            $this->visitRootNode($node);

            if($this->uniqIndexCollections->has($this->currentEntityIndex)) {
                $this->errors->add("At Sheet named '" . $this->getWorksheet()->getTitle(). "' Conflict between Indexes Values in Row " .
                    $this->uniqIndexCollections->get($this->currentEntityIndex) . " and " .
                    $this->row->getRowIndex(). " for the Datas named: '" . $node->getLabel() . "' ! Perhaps indexes values are missing ?")
                ;
                $this->currentEntityIndex = "";
            }
            else {
                $this->uniqIndexCollections->set($this->currentEntityIndex, $this->row->getRowIndex());
                $this->currentEntityIndex = "";
            }
        }

        return $this->errors->count() == 0;
    }

    public function visitRootNode(RootNode $node)
    {
        foreach ($node->getProperties()->getIterator() as $childNode) {
            /** @var AbstractNode $childNode */
            $childNode->accept($this);
        }
    }

    public function visitCollectionNode(CollectionNode $node)
    {
        foreach ($node->getProperties()->getIterator() as $childNode) {
            /** @var AbstractNode $childNode */
            $childNode->accept($this);
        }
    }

    public function visitEntityNode(EntityNode $node)
    {
        foreach ($node->getProperties()->getIterator() as $childNode) {
            /** @var AbstractNode $childNode */
            $childNode->accept($this);
        }
    }

    public function visitPropertyLeaf(PropertyLeaf $node)
    {
        //Si la feuille n'est pas un identifiant, on la passe, elle ne permet pas de construire un identifiant unique
        if($node->getProperty() !== 'id')
            return;

        $id =  $this->getWorksheet()->getCellByColumnAndRow($node->getCol(), $this->row->getRowIndex())->getValue();

        if($node->getParent()->getManifest()->get('type') === AbstractNode::ENTITY) {
            $explodedIds = explode("_", $this->currentEntityIndex);
            $holderId = $explodedIds[count($explodedIds) - 1];

            if(!$this->uniqEntityCollections->has($node->getParent()->getLabel())) {
                $this->uniqEntityCollections->set($node->getParent()->getLabel(), new ParameterBag([$holderId => $id]));
            }
            else {
                $bag = $this->uniqEntityCollections->get($node->getParent()->getLabel());

                if($bag->has($holderId) && $bag->get($holderId) !== $id) {
                    $this->errors->add("At Sheet named '" . $this->getWorksheet()->getTitle().
                        "' at line: ". $this->row->getRowIndex() ." Il ne peut pas y avoir deux '" . $node->getParent()->getLabel() .
                        "' de même identifiants pour les mêmes identifiants de la colonne '" .
                        $node->getParent()->getParent()->getLabel() . "'")
                    ;
                }
            }
        }
        $this->currentEntityIndex .= '_' . $id;


    }

    /**
     * @return Collection
     */
    public function getErrors() {
        return $this->errors;
    }
}