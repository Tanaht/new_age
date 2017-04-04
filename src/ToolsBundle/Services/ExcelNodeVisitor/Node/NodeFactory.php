<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 01/04/2017
 * Time: 13:50
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;



use Doctrine\Bundle\DoctrineBundle\Registry;
use JMS\Serializer\Exception\LogicException;
use Symfony\Component\DependencyInjection\Container;

class NodeFactory
{

    /**
     * @var NodeFactory
     */
    private static $nodeFactory;

    /**
     * @var Container
     */
    private $container;

    private function __construct(Container $container)
    {
        $this->container = $container;
    }

    public static function getFactory(Container $container = null) {
        if(self::$nodeFactory == null && $container == null) {
            throw new LogicException("When creating the factory singleton for the first time, the Container must be passe in parameters");
        }

        if(self::$nodeFactory == null)
            self::$nodeFactory = new NodeFactory($container);

        return self::$nodeFactory;
    }

    /**
     * @param string $identifier
     * @param array $manifest
     * @return EntityNode
     */
    public function createEntityNode($identifier, array $manifest, EntityNode $parent = null)
    {
        return new EntityNode($identifier, $manifest, $this->container->get('doctrine.orm.entity_manager'), $parent);
    }

    /**
     * @param string $identifier
     * @param array $manifest
     * @return AttributeNode
     */
    public function createAttributeNode($identifier, array $manifest, EntityNode $parent = null)
    {
        return new AttributeNode($identifier, $manifest, $this->container->get('doctrine.orm.entity_manager'), $parent);
    }

    /**
     * @param string $identifier
     * @param array $manifest
     * @return CollectionNode
     */
    public function createCollectionNode($identifier, array $manifest, EntityNode $parent = null)
    {
        return new CollectionNode($identifier, $manifest, $this->container->get('doctrine.orm.entity_manager'), $parent);
    }

    /**
     * @param $identifier
     * @param array $manifest
     * @param AbstractNode2|null $parent
     * @return AbstractNode2
     */
    public static function create($identifier, array $manifest, AbstractNode2 $parent = null) {
        switch($manifest['type']) {
            case AbstractNode2::PROPERTY:
                return new PropertyLeaf($identifier, $manifest, $parent);
                break;
            case AbstractNode2::ENTITY:
                //TODO: return EntityComponent
                break;
            case AbstractNode2::COLLECTION:
                //TODO: return CollectionComponent
                break;
            case AbstractNode2::ROOT:
                //TODO: return RootComponent
                break;
        }
    }
}