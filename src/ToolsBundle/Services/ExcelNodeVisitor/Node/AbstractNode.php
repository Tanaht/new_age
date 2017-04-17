<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 03/04/2017
 * Time: 20:36
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;


use Doctrine\ORM\EntityManager;
use JMS\Serializer\Exception\LogicException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\AbstractNodeVisitor;

abstract class AbstractNode
{
    const TABLE_OFFSET = 0;
    const IDENTIFIER_SEPARATOR = "_";

    /**
     * @var int
     */
    public static $incrementedId = 0;

    /**
     * @return int
     */
    public static function getUniqId() {
        return self::$incrementedId++;
    }

    const PROPERTY = "property";
    const COLLECTION = "collection";
    const ENTITY = "entity";
    const ROOT = "root";
    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $label;

    /**
     * @var int
     */
    private $depth;

    /**
     * @var AbstractComponent
     */
    private $parent;

    /**
     * @var ParameterBag
     */
    private $manifest;

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var ParameterBag
     */
    private $exportOptions;

    /**
     * @var ParameterBag
     */
    private $importOptions;
/*
 * Manifest:
 * [
 *      type: $nodeType
 *      label: $nodeLabel
 * ]
 */

    public function __construct($identifier, array $manifest, EntityManager $manager, AbstractComponent $parent = null)
    {

        $this->manager = $manager;
        $this->identifier = $identifier . self::IDENTIFIER_SEPARATOR . self::getUniqId();
        $this->parent = $parent;

        if($parent != null)
            $this->depth = $parent->getDepth() + 1;
        else
            $this->depth = 1;

        $resolver = new OptionsResolver();
        $this->configureManifest($resolver);

        try {
            $this->manifest = new ParameterBag($resolver->resolve($manifest));
        }
        catch(\Exception $e){
            throw new LogicException("Unable to construct node " . $this->identifier . " Reason: "  . $e->getMessage());
        }

        $this->exportOptions = new ParameterBag($this->manifest->get('export_options'));
        $this->importOptions = new ParameterBag($this->manifest->get('import_options'));

        $this->label = $this->manifest->get('label');
    }

    /**
     * @param AbstractNodeVisitor $visitor
     */
    public abstract function accept(AbstractNodeVisitor $visitor);

    /**
     * Return the computed width of the Node
     * @return int
     */
    public abstract function getWidth();

    /**
     * configure the Resolver Basic state
     * @param OptionsResolver $resolver
     */
    public function configureManifest(OptionsResolver $resolver) {
        $resolver->setRequired([
            'type',
            'label',
        ]);

        $resolver->setDefaults([
            'import_options' => [],
            'export_options' => []
        ]);

        $resolver->setAllowedTypes('type', 'string');
        $resolver->setAllowedTypes('import_options', 'array');
        $resolver->setAllowedTypes('export_options', 'array');

        $resolver->setAllowedTypes('label', 'string');

        $resolver->setAllowedValues('export_options', function(array $exportOptions) {
            $exportResolver = new OptionsResolver();
            $exportResolver->setDefined(['expr']);
            $exportResolver->setAllowedTypes('expr', 'array');
            $exportResolver->resolve($exportOptions);
            return true;
        });

        $resolver->setAllowedValues('type', [self::PROPERTY, self::ENTITY, self::COLLECTION, self::ROOT]);
    }

    /**
     * This function throw an exception if the manifest in parameter doesn't contain a valid key 'type'
     * @param array $manifest
     */
    public static function validateManifestType(array $manifest) {
        $resolver = new OptionsResolver();
        $resolver
            ->setRequired('type')
            ->setDefined(['label', 'properties', 'entity', 'property', 'import_options', 'export_options'])
            ->setAllowedTypes('type', 'string')
            ->setAllowedValues('type', [AbstractNode::PROPERTY, AbstractNode::ENTITY, AbstractNode::COLLECTION, AbstractNode::ROOT])
        ;

        $resolver->resolve($manifest);
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return AbstractComponent
     */
    public function getRootNode() {
        $node = $this;
        while($node->hasParent()) {
            $node = $node->getParent();
        }
        /** @var AbstractComponent $node */
        return $node;
    }

    /**
     * @return bool
     */
    public function hasParent() {
        return $this->parent !== null;
    }

    /**
     * @return AbstractComponent|null
     */
    public function getParent()
    {
        return $this->parent;
    }


    /**
     * @warning Return the maximum depth from the current node position (call this on RootNode for the appropriate result)
     * @return int
     */
    public abstract function getMaxDepth();

    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @return ParameterBag
     */
    public function getManifest()
    {
        return $this->manifest;
    }

    /**
     * @return EntityManager
     */
    public function getManager()
    {
        return $this->manager;
    }


    public function __toString()
    {
        return  "[" . $this->manifest->get('type') . "]:" . $this->identifier;
    }

    /**
     * @return ParameterBag
     */
    public function getExportOptions()
    {
        return $this->exportOptions;
    }

    /**
     * @param ParameterBag $exportOptions
     */
    public function setExportOptions($exportOptions)
    {
        $this->exportOptions = $exportOptions;
    }

    /**
     * @return ParameterBag
     */
    public function getImportOptions()
    {
        return $this->importOptions;
    }

    /**
     * @param ParameterBag $importOptions
     */
    public function setImportOptions($importOptions)
    {
        $this->importOptions = $importOptions;
    }

}