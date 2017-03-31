<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 31/03/2017
 * Time: 21:53
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;


use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\AbstractNodeVisitor;

class AttributeNode extends AbstractNode
{
    public  function initializeNode()
    {
        // TODO: Implement initializeNode() method.
    }

    public  function accept(AbstractNodeVisitor $visitor)
    {
        $visitor->visitAttributeNode($this);
    }

    public function configureOptions(OptionsResolver $resolver, Container $container = null)
    {
        parent::configureOptions($resolver, $container);
        $resolver->setRequired([
            'type',
            'property',
        ]);

        $resolver->setAllowedValues('type', self::ATTRIBUTE_TYPE);
        $resolver->setAllowedTypes('property', 'string');
    }

    /*public function accept(AttributeAbstractNodeVisitor $visitor)
    {
        parent::accept($visitor);
    }*/
}