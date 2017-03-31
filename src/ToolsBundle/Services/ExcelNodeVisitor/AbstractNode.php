<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 31/03/2017
 * Time: 20:54
 */

namespace ToolsBundle\Services\ExcelRowVisitor;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractNode
{
    const ENTITY_TYPE = "entity";
    const ATTRIBUTE_TYPE = "attribute";
    const COLLECTION_TYPE = "collection";

    /**
     * @var AbstractNode $parent
     */
    private $parent;

    /**
     * @var Collection
     */
    private $childrens;

    /**
     * @var string
     */
    private $label;

    /**
     * @var int $size
     */
    private $size;

    public function __construct()
    {
        $this->size = 0;
        $this->parent = null;
        $this->childrens = new ArrayCollection();
    }

    /**
     * @return bool
     */
    public function hasParent() {
        return $this->parent != null;
    }

    /**
     * @return AbstractNode
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * @return int
     */
    private function getSize() {
        $count = 0;

        $this->childrens->forAll(function(AbstractNode $node) use($count){
            $count += $node->getSize();
            return true;
        });

        return $count;
    }
    public function accept(NodeVisitor $visitor) {
        $visitor->visit($this);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setRequired([
            'type',
            'label'
        ]);

        $resolver->setAllowedTypes('label', 'string');
        $resolver->setAllowedValues('type', [self::ENTITY_TYPE, self::COLLECTION_TYPE, self::ATTRIBUTE_TYPE]);
    }
}