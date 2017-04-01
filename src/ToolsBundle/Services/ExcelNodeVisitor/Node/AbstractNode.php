<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 31/03/2017
 * Time: 20:54
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\AbstractNodeVisitor;

abstract class AbstractNode implements ContainerAwareInterface
{
    const ENTITY_TYPE = "entity";
    const ATTRIBUTE_TYPE = "attribute";
    const COLLECTION_TYPE = "collection";

    use ContainerAwareTrait;
    /**
     * @var AbstractNode $parent
     */
    private $parent;

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var array
     */
    private $manifest;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $identifier;

    public function __construct($identifier, array $manifest, EntityManager $manager, AbstractNode $parent = null)
    {
        $this->identifier = $identifier;
        $this->parent = $parent;
        $this->label = $manifest['label'];
        $this->manifest = $manifest;
        $this->manager = $manager;
    }

    /**
     * @return bool
     */
    public function hasParent() {
        return $this->parent != null;
    }

    /**
     * Return the parent node of this node
     * @return AbstractNode
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Return the width that this node takes on an Excel Row
     * @return int
     */
    public abstract function getWidth();

    /**
     * Allow A visitor to visit this Node to do some task
     * @param AbstractNodeVisitor $visitor
     * @return void
     */
    public abstract function accept(AbstractNodeVisitor $visitor);

    /**
     * Configure the resolver to check the manifest file
     * @param OptionsResolver $resolver
     * @param Container|null $container
     */
    public function configureManifest(OptionsResolver $resolver) {
        $resolver->setRequired([
            'type',
            'label'
        ]);

        $resolver->setAllowedTypes('label', 'string');
        $resolver->setAllowedValues('type', [self::ENTITY_TYPE, self::COLLECTION_TYPE, self::ATTRIBUTE_TYPE]);
    }

    /**
     * Return a manifest description of a Node, the returned array have a key 'type' which is one of these: Entity, Collection, Attribute constants of the AbsractNode class
     * @param $manifest
     * @return array The manifest description of the node
     * @throws UndefinedOptionsException	If an option name is undefined
     * @throws InvalidOptionsException	    If an option doesn't fulfill the specified validation rules
     * @throws MissingOptionsException	    If a required option is missing
     * @throws OptionDefinitionException	If there is a cyclic dependency between lazy options and/or normalizers
     * @throws NoSuchOptionException	    If a lazy option reads an unavailable option
     * @throws AccessException	            If called from a lazy option or normalizer
     */
    public static function getValidManifest($manifest) {
        $resolver = new OptionsResolver();

        $resolver->setRequired([
            'type',
            'label'
        ]);

        $resolver->setDefined([
            'property',
            'class',
            'childrens',
            'reference'
        ]);


        $resolver->setAllowedTypes('label', 'string');
        $resolver->setAllowedValues('type', [self::ENTITY_TYPE, self::COLLECTION_TYPE, self::ATTRIBUTE_TYPE]);

        return $resolver->resolve($manifest);

    }

    /**
     * @return array
     */
    public function getManifest()
    {
        return $this->manifest;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->doctrine = $container->get('doctrine');
    }


    /**
     * @return EntityManager
     */
    public function getManager() {
        return $this->manager;
    }
}