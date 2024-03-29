<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 04/04/2017
 * Time: 13:00
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;


use Doctrine\ORM\EntityManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\AbstractNodeVisitor;

class PropertyLeaf extends AbstractLeaf
{

    /**
     * @param AbstractNodeVisitor $visitor
     */
    public function accept(AbstractNodeVisitor $visitor)
    {
        $visitor->visitPropertyLeaf($this);
    }

    public function configureManifest(OptionsResolver $resolver)
    {
        parent::configureManifest($resolver);
        $resolver->setAllowedValues('type', self::PROPERTY);
    }
}