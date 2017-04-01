<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 31/03/2017
 * Time: 21:53
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\AbstractNodeVisitor;

class AttributeNode extends AbstractNode
{
    private $property;

    public function __construct($identifier, array $manifest, EntityManager $manager, AbstractNode $parent = null)
    {
        parent::__construct($identifier, $manifest, $manager, $parent);

        $resolver = new OptionsResolver();

        $this->configureManifest($resolver);

        $manifest = $resolver->resolve($manifest);

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
    }
}