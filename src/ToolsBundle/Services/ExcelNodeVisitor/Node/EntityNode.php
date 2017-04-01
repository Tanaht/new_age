<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 31/03/2017
 * Time: 21:52
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\PropertyInfo\DoctrineExtractor;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\AbstractNodeVisitor;

class EntityNode extends AbstractNode
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var string
     */
    private $class;
    /**
     * @var Collection
     */
    private $childrens;

    public function __construct($identifier, array $manifest, EntityManager $manager, AbstractNode $parent = null)
    {
        parent::__construct($identifier, $manifest, $manager, $parent);

        $resolver = new OptionsResolver();

        $this->configureManifest($resolver);

        $manifest = $resolver->resolve($manifest);

        if($this->hasParent())
            $this->property = $manifest['property'];
        else
            $this->property = null;

        $this->class = $manifest['class'];

        $this->childrens = new ArrayCollection();

        foreach ($manifest['childrens'] as $nodeIdentifier => $nodeManifest) {
            $nodeManifest = AbstractNode::getValidManifest($nodeManifest);

            switch($nodeManifest['type']) {
                case self::ATTRIBUTE_TYPE:
                    $this->childrens->add(NodeFactory::getFactory()->createAttributeNode($nodeIdentifier, $nodeManifest, $this));
                    break;
                case self::ENTITY_TYPE:
                    $this->childrens->add(NodeFactory::getFactory()->createEntityNode($nodeIdentifier, $nodeManifest, $this));
                    break;
                case self::COLLECTION_TYPE:
                    $this->childrens->add(NodeFactory::getFactory()->createCollectionNode($nodeIdentifier, $nodeManifest, $this));
                    break;
            }
        }
    }

    public  function accept(AbstractNodeVisitor $visitor)
    {
        $visitor->visitEntityNode($this);
    }

    public function configureManifest(OptionsResolver $resolver)
    {
        parent::configureManifest($resolver);
        $resolver->setRequired([
            'type',
            'class',
            'childrens'
        ]);

        $resolver->setAllowedValues('class', function($value) {
            return !self::getManager()->getMetadataFactory()->isTransient($value);
        });

        if($this->hasParent()) {
            $resolver->setRequired('property');
            $resolver->setAllowedTypes('property', 'string');
        }


        $resolver->setAllowedTypes('childrens', 'array');
        $resolver->setAllowedValues('type', self::ENTITY_TYPE);
    }

    public  function getWidth()
    {
        $count = 0;

        $this->childrens->forAll(function(AbstractNode $node) use($count){
            $count += $node->getWidth();
            return true;
        });

        return $count;
    }
}