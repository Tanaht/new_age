<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 31/03/2017
 * Time: 21:52
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;


use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\AbstractNodeVisitor;

class EntityNode extends AbstractNode
{
    /**
     * @var string $identifier
     */
    private $identifier;

    /**
     * EntityNode constructor.
     * @param string $identifier
     */
    public function __construct($identifier)
    {
        parent::__construct();
        $this->identifier = $identifier;
    }

    public  function accept(AbstractNodeVisitor $visitor)
    {
        $visitor->visitEntityNode($this);
    }

    public  function initializeNode()
    {
         $this->setLabel($this->getManifest()['label']);
         $this->setChildrens($this->getManifest()['childrens']);
    }

    public function configureOptions(OptionsResolver $resolver, Container $container = null)
    {
        parent::configureOptions($resolver, $container);
        $resolver->setRequired([
            'type',
            'class',
            'childrens'
        ]);

        if($this->hasParent()) {
            $resolver->setRequired('property');
            $resolver->setAllowedTypes('property', 'string');
        }

        if($container != null) {
            $resolver->setAllowedValues('class', function($value) use($container) {
                return !$container->get('doctrine.orm.entity_manager')->getMetadataFactory()->isTransient($value);
            });
        }

        $resolver->setAllowedTypes('childrens', 'array');
        $resolver->setAllowedValues('type', self::ENTITY_TYPE);
    }
}