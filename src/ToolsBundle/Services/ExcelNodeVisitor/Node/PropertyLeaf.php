<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 04/04/2017
 * Time: 13:00
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;


use Symfony\Component\OptionsResolver\OptionsResolver;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\AbstractNodeVisitor;

class PropertyLeaf extends AbstractLeaf
{
    /**
     * @var string
     */
    private $property;

    public function __construct($identifier, array $manifest, AbstractComponent $parent = null)
    {
        parent::__construct($identifier, $manifest, $parent);
        $this->property = $this->getManifest()->get('property');
    }

    public function configureManifest(OptionsResolver $resolver)
    {
        parent::configureManifest($resolver);

        $resolver->setRequired('property');
        //TODO: Validate Property Options
        $resolver->setAllowedTypes('property', 'string');
        $resolver->setAllowedValues('type', self::PROPERTY);

    }


    /**
     * @param AbstractNodeVisitor $visitor
     */
    public function accept(AbstractNodeVisitor $visitor)
    {
        $visitor->visitPropertyLeaf($this);
    }
}