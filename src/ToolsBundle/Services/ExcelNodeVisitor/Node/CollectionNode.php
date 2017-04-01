<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 31/03/2017
 * Time: 21:53
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
use ToolsBundle\Services\ExcelNodeVisitor\VIsitor\AbstractNodeVisitor;

class CollectionNode extends AbstractNode
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var string
     */
    private $reference;

    /**
     * @var AbstractNode
     */
    private $referencedNode;

    public function __construct($identifier, array $manifest, EntityManager $manager, EntityNode $parent = null)
    {
        parent::__construct($identifier, $manifest, $manager, $parent);

        try {
            $resolver = new OptionsResolver();

            $this->configureManifest($resolver);

            $manifest = $resolver->resolve($manifest);
        }
        catch(UndefinedOptionsException $e) {
            throw new UndefinedOptionsException($e->getMessage() . " on CollectionNode $this", $e->getCode());
        }
        catch(InvalidOptionsException $e) {
            throw new InvalidOptionsException($e->getMessage() . " on CollectionNode $this", $e->getCode());
        }
        catch(MissingOptionsException $e) {
            throw new MissingOptionsException($e->getMessage() . " on CollectionNode $this", $e->getCode());
        }
        catch(OptionDefinitionException $e) {
            throw new OptionDefinitionException($e->getMessage() . " on CollectionNode $this", $e->getCode());
        }
        catch(NoSuchOptionException $e) {
            throw new NoSuchOptionException($e->getMessage() . " on CollectionNode $this", $e->getCode());
        }
        catch(AccessException $e) {
            throw new AccessException($e->getMessage() . " on CollectionNode $this", $e->getCode());
        }

        $this->referencedNode = null;
        $this->property = $manifest['property'];
        $this->reference = $manifest['reference'];
    }


    /*
     * @throws UndefinedOptionsException	If an option name is undefined
     * @throws InvalidOptionsException	    If an option doesn't fulfill the specified validation rules
     * @throws MissingOptionsException	    If a required option is missing
     * @throws OptionDefinitionException	If there is a cyclic dependency between lazy options and/or normalizers
     * @throws NoSuchOptionException	    If a lazy option reads an unavailable option
     * @throws AccessException	            If called from a lazy option or normalizer
     * */

    /**
     * @InheritDoc
     */
    public  function accept(AbstractNodeVisitor $visitor)
    {
        $visitor->visitCollectionNode($this);
    }

    /**
     * @InheritDoc
     */
    public function configureManifest(OptionsResolver $resolver)
    {
        parent::configureManifest($resolver);
        $resolver->setRequired([
            'type',
            'property',
            'reference',
        ]);

        $resolver->setAllowedTypes('reference', 'string');

        $resolver->setAllowedValues('type', self::COLLECTION_TYPE);

        $resolver->setAllowedTypes('property', 'string');

        $resolver->setAllowedValues('property', function($property) {
            $extractor = new DoctrineExtractor($this->getManager()->getMetadataFactory());

            $parentClassProperties = new ArrayCollection($extractor->getProperties($this->getParent()->getClass()));

            return $parentClassProperties->contains($property);
        });

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
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @return AbstractNode
     */
    public function getReferencedNode()
    {
        return $this->referencedNode;
    }

    /**
     * @param AbstractNode $referencedNode
     */
    public function setReferencedNode($referencedNode)
    {
        $this->referencedNode = $referencedNode;
    }





}