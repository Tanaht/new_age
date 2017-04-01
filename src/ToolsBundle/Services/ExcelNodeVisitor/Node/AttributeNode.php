<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 31/03/2017
 * Time: 21:53
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;


use Doctrine\Common\Collections\ArrayCollection;
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
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\AbstractNodeVisitor;

class AttributeNode extends AbstractNode
{
    private $property;

    public function __construct($identifier, array $manifest, EntityManager $manager, EntityNode $parent = null)
    {
        parent::__construct($identifier, $manifest, $manager, $parent);

        try {
            $resolver = new OptionsResolver();

            $this->configureManifest($resolver);

            $manifest = $resolver->resolve($manifest);
        }
        catch(UndefinedOptionsException $e) {
            throw new UndefinedOptionsException($e->getMessage() . " on AttributeNode $this", $e->getCode());
        }
        catch(InvalidOptionsException $e) {
            throw new InvalidOptionsException($e->getMessage() . " on AttributeNode $this", $e->getCode());
        }
        catch(MissingOptionsException $e) {
            throw new MissingOptionsException($e->getMessage() . " on AttributeNode $this", $e->getCode());
        }
        catch(OptionDefinitionException $e) {
            throw new OptionDefinitionException($e->getMessage() . " on AttributeNode $this", $e->getCode());
        }
        catch(NoSuchOptionException $e) {
            throw new NoSuchOptionException($e->getMessage() . " on AttributeNode $this", $e->getCode());
        }
        catch(AccessException $e) {
            throw new AccessException($e->getMessage() . " on AttributeNode $this", $e->getCode());
        }

        $this->property = $manifest['property'];
    }


    public  function accept(AbstractNodeVisitor $visitor)
    {
        $visitor->visitAttributeNode($this);
    }

    public  function getWidth()
    {
        return 1;
    }

    public function configureManifest(OptionsResolver $resolver)
    {
        parent::configureManifest($resolver);
        $resolver->setRequired([
            'type',
            'property',
        ]);

        $resolver->setAllowedValues('type', self::ATTRIBUTE_TYPE);
        $resolver->setAllowedTypes('property', 'string');

        $resolver->setAllowedValues('property', function($property) {
            $extractor = new DoctrineExtractor($this->getManager()->getMetadataFactory());

            $parentClassProperties = new ArrayCollection($extractor->getProperties($this->getParent()->getClass()));

            return $parentClassProperties->contains($property);
        });
    }
}