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
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
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
        try {
            $resolver = new OptionsResolver();

            $this->configureManifest($resolver);

            $manifest = $resolver->resolve($manifest);
        }
        catch(UndefinedOptionsException $e) {
            throw new UndefinedOptionsException($e->getMessage() . " on EntityNode $this", $e->getCode());
        }
        catch(InvalidOptionsException $e) {
            throw new InvalidOptionsException($e->getMessage() . " on EntityNode $this", $e->getCode());
        }
        catch(MissingOptionsException $e) {
            throw new MissingOptionsException($e->getMessage() . " on EntityNode $this", $e->getCode());
        }
        catch(OptionDefinitionException $e) {
            throw new OptionDefinitionException($e->getMessage() . " on EntityNode $this", $e->getCode());
        }
        catch(NoSuchOptionException $e) {
            throw new NoSuchOptionException($e->getMessage() . " on EntityNode $this", $e->getCode());
        }
        catch(AccessException $e) {
            throw new AccessException($e->getMessage() . " on EntityNode $this", $e->getCode());
        }

        if($this->hasParent())
            $this->property = $manifest['property'];
        else
            $this->property = null;

        $this->class = $manifest['class'];

        $this->childrens = new ArrayCollection();

        $this->childrens->add(NodeFactory::getFactory()->createAttributeNode('id', [
            'property' => 'id',
            'label' => 'ID',
            'type' => self::ATTRIBUTE_TYPE,
        ], $this));

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

            $resolver->setAllowedValues('property', function($key, $property) {
                $extractor = new DoctrineExtractor($this->getManager()->getMetadataFactory());

                $parentClassProperties = new ArrayCollection($extractor->getProperties($this->getParent()->getClass()));

                return $parentClassProperties->contains($property);
            });


        }


        $resolver->setAllowedTypes('childrens', 'array');
        $resolver->setAllowedValues('type', self::ENTITY_TYPE);
    }

    public  function getWidth()
    {
        $count = 0;
        foreach ($this->childrens->getIterator() as $childNode) {
            $count += $childNode->getWidth();
        }

        return $count;
    }


    /**
     * Return the maximum height between this nodes and the end of the subnodes
     * @return int
     */
    public function getHeight() {

        $maximumDepth = $this->getDepth();
        foreach ($this->childrens->getIterator() as $childNode) {
            $maximumDepth = max($maximumDepth, $childNode->getHeight());
        }

        return $maximumDepth;
    }


    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return Collection
     */
    public function getChildrens()
    {
        return $this->childrens;
    }


}