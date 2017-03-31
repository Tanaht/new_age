<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 31/03/2017
 * Time: 21:52
 */

namespace ToolsBundle\Services\ExcelNodeVisitor;


use Symfony\Component\OptionsResolver\OptionsResolver;
use ToolsBundle\Services\ExcelRowVisitor\AbstractNode;

class EntityNode extends AbstractNode
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setRequired([
            'type',
            'class',
            'childrens'
        ]);

        if($this->hasParent()) {
            $resolver->setRequired('property');
            $resolver->setAllowedTypes('property', 'string');
        }
        $resolver->setAllowedTypes('class', 'class');
        $resolver->setAllowedTypes('childrens', 'array');
        $resolver->setAllowedValues('type', self::ENTITY_TYPE);
    }
}