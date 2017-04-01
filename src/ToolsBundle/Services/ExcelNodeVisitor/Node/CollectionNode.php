<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 31/03/2017
 * Time: 21:53
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;


use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ToolsBundle\Services\ExcelNodeVisitor\VIsitor\AbstractNodeVisitor;

class CollectionNode extends AbstractNode
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $reference;

    public function __construct($identifier, array $manifest, EntityManager $manager, AbstractNode $parent = null)
    {
        parent::__construct($identifier, $manifest, $manager, $parent);

        $resolver = new OptionsResolver();

        $this->configureManifest($resolver);

        $manifest = $resolver->resolve($manifest);

        $this->property = $manifest['property'];
        $this->class = $manifest['class'];
        $this->reference = $manifest['reference'];
    }


    public  function accept(AbstractNodeVisitor $visitor)
    {
        $visitor->visitCollectionNode($this);
    }

    public  function getWidth()
    {
        return 1;
    }

    public function configureManifest(OptionsResolver $resolver)
    {
        parent::configureManifest($resolver);
        $resolver->setRequired([
            'type',
            'property',
            'class',
            'reference',
        ]);

        $resolver->setAllowedTypes('property', 'string');
        $resolver->setAllowedTypes('reference', 'string');

        $resolver->setAllowedValues('class', function($value) {
            return !self::getManager()->getMetadataFactory()->isTransient($value);
        });

        $resolver->setAllowedValues('type', self::COLLECTION_TYPE);

    }
}