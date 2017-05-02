<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 04/04/2017
 * Time: 17:42
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;


use Doctrine\ORM\EntityManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\AbstractNodeVisitor;

class CollectionNode extends AbstractComponent
{
    use PropertyTrait;

    public function __construct($identifier, array $manifest, EntityManager $manager, AbstractComponent $parent = null)
    {
        parent::__construct($identifier, $manifest, $manager, $parent);
        $this->property = $this->getManifest()->get('property');
    }

    /**
     * @param AbstractNodeVisitor $visitor
     */
    public function accept(AbstractNodeVisitor $visitor)
    {
        $visitor->visitCollectionNode($this);
    }

    public function configureManifest(OptionsResolver $resolver)
    {
        parent::configureManifest($resolver);
        $this->configurePropertyManifest($this->getParent()->getMetadata(), $resolver);
        $resolver->setAllowedValues('type', self::COLLECTION);
    }
}