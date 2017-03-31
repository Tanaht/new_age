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

class AttributeNode extends AbstractNode
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setRequired([
            'type',
            'property',
        ]);

        $resolver->setAllowedValues('type', self::ATTRIBUTE_TYPE);
        $resolver->setAllowedTypes('property', 'string');
    }
}