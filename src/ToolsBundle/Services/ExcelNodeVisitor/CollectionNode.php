<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 31/03/2017
 * Time: 21:53
 */

namespace ToolsBundle\Services\ExcelNodeVisitor;


use Symfony\Component\OptionsResolver\OptionsResolver;
use ToolsBundle\Services\ExcelRowVisitor\AbstractNode;

class CollectionNode extends AbstractNode
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setRequired([
            'type',
            'property',
            'class',
            'reference'
        ]);

        $resolver->setAllowedTypes('property', 'string');
        $resolver->setAllowedTypes('reference', 'string');
        $resolver->setAllowedTypes('class', 'class');
        $resolver->setAllowedValues('type', self::COLLECTION_TYPE);

    }
}