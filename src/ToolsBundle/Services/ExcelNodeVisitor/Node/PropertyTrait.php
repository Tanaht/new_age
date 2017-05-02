<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 04/04/2017
 * Time: 18:14
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;


use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait PropertyTrait
{
    private $property;

    public function configurePropertyManifest(ClassMetadata $metadata, OptionsResolver $resolver)
    {

        $resolver->setRequired('property');

        $resolver->setAllowedTypes('property', 'string');

        $resolver->setAllowedValues('property', function($propetyName) use($metadata) {
            return -1 !== array_key_exists($propetyName, $metadata->getFieldNames());
        });

    }

    public function getProperty() {
        return $this->property;
    }
}