<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 31/03/2017
 * Time: 22:35
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Visitor;


use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AttributeNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\CollectionNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\EntityNode;

class InstanciationNodeVisitor extends AbstractNodeVisitor
{

    /**
     * @var array $entityOptions
     */
    private $entityOptions;

    /**
     * @var $container Container
     */
    private $container;

    public function __construct(Container $container, array $entityOptions)
    {
        $this->entityOptions = $entityOptions;
        $this->container = $container;
    }

    public function visitEntityNode(EntityNode $node)
    {
        $resolver = new OptionsResolver();
        $node->configureOptions($resolver, $this->container);

        $this->entityOptions = $resolver->resolve($this->entityOptions);

        $node->setManifest($this->entityOptions);

    }

    public function visitAttributeNode(AttributeNode $node)
    {
        dump($node);
    }

    public function visitCollectionNode(CollectionNode $node)
    {
        dump($node);
    }
}