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
use ToolsBundle\Services\ExcelNodeVisitor\VIsitor\AbstractNodeVisitor;

class CollectionNode extends AbstractNode
{
    public  function initializeNode()
    {
        // TODO: Implement initializeNode() method.
    }

    public  function accept(AbstractNodeVisitor $visitor)
    {
        $visitor->visitCollectionNode($this);
    }

    public function configureOptions(OptionsResolver $resolver, Container $container = null)
    {
        parent::configureOptions($resolver, $container);
        $resolver->setRequired([
            'type',
            'property',
            'class',
            'reference'
        ]);

        $resolver->setAllowedTypes('property', 'string');
        $resolver->setAllowedTypes('reference', 'string');

        if($container != null) {
            $resolver->setAllowedValues('class', function($value) use($container) {
                return !$container->get('doctrine.orm.entity_manager')->getMetadataFactory()->isTransient($value);
            });
        }

        $resolver->setAllowedValues('type', self::COLLECTION_TYPE);

    }
}