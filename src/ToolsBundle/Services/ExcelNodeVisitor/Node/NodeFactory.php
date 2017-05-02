<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 01/04/2017
 * Time: 13:50
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;


use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\LogicException;

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
     * @param $identifier
     * @param array $manifest
     * @return RootNode
     */
    public function createRootNode($identifier, array $manifest) {
        return new RootNode($identifier, $manifest, $this->container->get('doctrine.orm.entity_manager'));
    }

    /**
     * @param $identifier
     * @param array $manifest
     * @param AbstractNode|null $parent
     * @return AbstractNode
     * @throws LogicException
     */
    public function create($identifier, array $manifest, AbstractNode $parent = null) {
        switch($manifest['type']) {
            case AbstractNode::PROPERTY:
                return new PropertyLeaf($identifier, $manifest, $this->container->get('doctrine.orm.entity_manager'), $parent);
                break;
            case AbstractNode::ENTITY:
                return new EntityNode($identifier, $manifest, $this->container->get('doctrine.orm.entity_manager'), $parent);
                break;
            case AbstractNode::COLLECTION:
                return new CollectionNode($identifier, $manifest, $this->container->get('doctrine.orm.entity_manager'), $parent);
                break;
            default:
                throw new LogicException("Le type '" . $manifest['type'] . "' du noeud à instancier est inconnue");
        }
    }
}