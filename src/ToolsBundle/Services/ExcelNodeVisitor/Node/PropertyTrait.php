<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 04/04/2017
 * Time: 18:14
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;


use Symfony\Component\OptionsResolver\OptionsResolver;

trait PropertyTrait
{
    private $property;

    public function configurePropertyManifest(OptionsResolver $resolver)
    {

        $resolver->setRequired('property');
        //TODO: Validate Property Options
        $resolver->setAllowedTypes('property', 'string');

    }

    public function getProperty() {
        return $this->property;
    }
}