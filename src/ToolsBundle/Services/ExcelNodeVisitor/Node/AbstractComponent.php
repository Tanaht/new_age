<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 03/04/2017
 * Time: 20:32
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractComponent extends AbstractNode
{
    /**
     * @var Collection
     */
    private $properties;

    /**
     * @var ClassMetadata
     */
    private $metadata;

    public function __construct($identifier, array $manifest, EntityManager $manager, AbstractComponent $parent = null)
    {
        parent::__construct($identifier, $manifest, $manager, $parent);
        $this->properties = new ArrayCollection();
        $this->metadata = $this->getManager()->getClassMetadata($manifest['entity']);

        $properties = new ArrayCollection($this->getManifest()->get('properties'));


        //Adding ID field if not found in subproperties
        if($properties->filter(function(array $propertyManifest) {
            return $propertyManifest['property'] === 'id' && $propertyManifest['type'] === self::PROPERTY;
        })->count() == 0) {
            $this->properties->add(NodeFactory::getFactory()->create('identity', [
                'property' => 'id',
                'type' => self::PROPERTY,
                'label' => 'ID'
            ], $this));
        }

        foreach($properties as $identifier => $value) {
            self::validateManifestType($value);
            $this->properties->add(NodeFactory::getFactory()->create($identifier, $value, $this));
        }

    }

    public function configureManifest(OptionsResolver $resolver)
    {
        parent::configureManifest($resolver);

        $resolver->setRequired(['entity', 'properties']);

        $resolver->setAllowedTypes('entity', 'string');
        $resolver->setAllowedTypes('properties', 'array');

        $resolver->setAllowedValues('entity', function($className) {
            return !$this->getManager()->getMetadataFactory()->isTransient($className);
        });
    }

    public  function getWidth()
    {
        $width = 0;
        foreach ($this->properties->getIterator() as $childNode) {

            /** @var AbstractNode $childNode */
            $width += $childNode->getWidth();

        }
        return $width;
    }

    /**
     * @return Collection
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return ClassMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return int
     */
    public  function getMaxDepth()
    {
        $depth = 0;

        foreach ($this->getProperties()->getIterator() as $childNode) {
            /** @var AbstractNode $childNode */
            $depth = max($depth, $childNode->getMaxDepth());
        }

        return $depth;
    }
}