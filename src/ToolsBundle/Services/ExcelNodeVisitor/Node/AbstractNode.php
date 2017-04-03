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
use Symfony\Component\HttpFoundation\ParameterBag;
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
    const TABLE_OFFSET = 2;
    use ContainerAwareTrait;
    /**
     * @var EntityNode $parent
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
     * @var ParameterBag
     */
    private $importOptions;

    /**
     * @var ParameterBag
     */
    private $exportOptions;

    /**
     * Représente la profondeur
     * @var int
     */
    private $depth;

    /**
     * @var string
     */
    private $identifier;

    public function __construct($identifier, array $manifest, EntityManager $manager, EntityNode $parent = null)
    {

        $this->identifier = $identifier;
        $this->parent = $parent;
        $this->label = $manifest['label'];
        $this->manifest = $manifest;
        $this->manager = $manager;
        $this->importOptions = new ParameterBag();
        $this->exportOptions = new ParameterBag();

        if(array_key_exists('import_options', $manifest))
            $this->importOptions->add($manifest['import_options']);

        if(array_key_exists('export_options', $manifest))
            $this->exportOptions->add($manifest['export_options']);

        if($this->hasParent()) {
            $this->depth = 1 + $this->getParent()->getDepth();
        }
        else {
            $this->depth = 1;
        }
    }

    /**
     * @return bool
     */
    public function hasParent() {
        return $this->parent != null;
    }

    /**
     * Return the parent node of this node
     * @return EntityNode
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Return the width that this node takes on an Excel Row
     * @return int
     */
    public function getWidth() {
        return 1;
    }

    /**
     * Return the maximum height between this nodes and the end of the subnodes
     * @return int
     */
    public function getHeight() {
        return $this->depth;
    }

    /**
     * Return the root node of this tree.
     * @return EntityNode
     */
    public function getRootNode() {
        $rootNode = $this;
        while($rootNode->hasParent()) {
            $rootNode = $rootNode->getParent();
        }

        /** @var EntityNode $rootNode */
        return $rootNode;
    }
    /**
     * Return the row offset relative to the RootNode
     * @return int
     */
    public function getCol($entityOffset = 0) {
        return $entityOffset + $this->getRootNode()->getRelativeCol($this);
    }

    /**
     * This is called by getRow() and traverse all the nodes until it found the node equals to $node param to compute the col offset needed for excel Imports/Exports
     * @param AbstractNode $node
     * @return int
     */
    private function getRelativeCol(AbstractNode $node) {
        if($this instanceof EntityNode) {
            if($this === $node)
                return 0;


            $count = 0;
            foreach ($this->getChildrens()->getIterator() as $childNode) {
                if($childNode === $node)
                    return $count;

                /** @var AbstractNode $childNode */
                $count += $childNode->getWidth();
            }

            return $count;
        }
        else {
            if($node === $this)
                return 0;
            return 1;
        }
    }

    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }


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
            'label',
        ]);

        $resolver->setDefaults([
            'import_options' => [],
            'export_options' => [],
        ]);

        $resolver->setAllowedTypes('export_options', 'array');
        $resolver->setAllowedTypes('import_options', 'array');

        $resolver->setAllowedValues('export_options', function(array $exportOptions) {
            $exportOptionsResolver = new OptionsResolver();
            $exportOptionsResolver->setDefaults(['expr' => []]);
            $exportOptionsResolver->setAllowedTypes('expr', 'array');
            return $exportOptionsResolver->resolve($exportOptions);
        });

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

        $resolver->setDefaults([
            'import_options' => [],
            'export_options' => ['expr' => []],
        ]);

        $resolver->setAllowedTypes('export_options', 'array');
        $resolver->setAllowedTypes('import_options', 'array');

        $resolver->setAllowedValues('export_options', function(array $exportOptions) {
            $exportOptionsResolver = new OptionsResolver();
            $exportOptionsResolver->setDefaults(['expr' => []]);
            $exportOptionsResolver->setAllowedTypes('expr', 'array');
            return $exportOptionsResolver->resolve($exportOptions);
        });

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
     * @return ParameterBag
     */
    public function getImportOptions()
    {
        return $this->importOptions;
    }

    /**
     * @return ParameterBag
     */
    public function getExportOptions()
    {
        return $this->exportOptions;
    }


    /**
     * @return EntityManager
     */
    public function getManager() {
        return $this->manager;
    }

   public function __toString()
   {
       return "[" . $this->identifier . "]";
   }
}