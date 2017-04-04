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

        foreach ($this->getManifest()->get('properties') as $identifier => $value) {
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


}