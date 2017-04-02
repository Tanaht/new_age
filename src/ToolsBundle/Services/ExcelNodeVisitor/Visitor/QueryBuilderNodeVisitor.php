<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 02/04/2017
 * Time: 00:25
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Visitor;


use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Repository\RepositoryFactory;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use ToolsBundle\Services\ExcelMappingParser\ExcelManifest;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AbstractNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AttributeNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\CollectionNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\EntityNode;


class QueryBuilderNodeVisitor extends AbstractNodeVisitor
{
    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var ExcelManifest
     */
    private $manifest;

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * Bag of EntityIdentifier => Entity alias
     * @var ParameterBag
     */
    private $aliasesLibrary;

    /**
     * Bag of QueryParameters => Key Value
     * @var ParameterBag
     */
    private $parametersLibrary;

    /**
     * QueryBuilderNodeVisitor constructor.
     * @param ExcelManifest $manifest
     * @param EntityManager $manager
     */
    public function __construct(ExcelManifest $manifest, EntityManager $manager)
    {
        $this->manager = $manager;
        $this->manifest = $manifest;
        $this->reinitializeState();
    }


    public function visitEntityNode(EntityNode $node)
    {
        if(!$node->hasParent()) {
            //RootNode
            if($this->manifest->getEntityInfos($node->getIdentifier())->get('reference')) {
                //if is a reference

            }
            else {
                //if is THE Root Node
                $className = $this->manager->getClassMetadata($node->getClass())->getName();
                $this->queryBuilder->select($this->getAlias($node));
                $this->queryBuilder->from($className, $this->getAlias($node));
            }
        }
        else {
            $alias = $this->getAlias($node);
            $referencedAlias = $this->getAlias($node, false);
            $this->queryBuilder->innerJoin($alias . $node->getProperty(), $referencedAlias);
        }


        foreach ($node->getChildrens()->getIterator() as $childrenNode) {
            /**@var AbstractNode $childrenNode */
            $childrenNode->accept($this);
        }
    }

    public function visitAttributeNode(AttributeNode $node)
    {
        $alias = $this->getAlias($node);

        //This line breaks Doctrine Object Hydration Mode
        //$this->queryBuilder->addSelect($alias . '.' . $node->getProperty());

        if($node->getExportOptions()->has('expr'))
            $this->buildPropertyExpression($alias . "." . $node->getProperty(), $node->getExportOptions()->get('expr'));

    }

    public function visitCollectionNode(CollectionNode $node)
    {
        $alias = $this->getAlias($node);
        $referencedAlias = $this->getAlias($node->getReferencedNode());

        $this->queryBuilder->innerJoin($alias . "." . $node->getProperty(), $referencedAlias);
        //build the subquery of the referenced node
        $node->getReferencedNode()->accept($this);
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getQuery() {
        $query = $this->queryBuilder->getQuery();
        $query->setParameters($this->parametersLibrary->all());
        $this->reinitializeState();
        dump($query->getResult());
        dump($query->getDQL());
        return $query;
    }


    /**
     * @param $value
     * @return string the key associated with the value in parameter by this function
     */
    private function getParameterKey($value) {
        $count = $this->parametersLibrary->count();
        $key = ':key_' . $count;
        $this->parametersLibrary->set($key, $value);
        return $key;
    }
    /**
     * Return the aliases generated for the rootNode of the node given in parameter
     * @param EntityNode $node
     * @param boolean $rootNodeAlias on some case it is necessary to generate an alias for an Entity Node that is not the RootNode
     * @return string the alias of this Entity
     */
    private function getAlias(AbstractNode $node, $rootNodeAlias = true) {
        if($rootNodeAlias == true)
            $rootNode = $node->getRootNode();
        else
            $rootNode = $node;

        if(get_class($rootNode) !== EntityNode::class)
            throw new InvalidPropertyPathException("Trying to generate an alias for something that is not an EntityNode: " . get_class($rootNode));

        if(!$this->aliasesLibrary->has($rootNode->getIdentifier())) {
            $alias = 'A' . $this->aliasesLibrary->count();
            $this->aliasesLibrary->set($rootNode->getIdentifier(), $alias);
            return $alias;
        }

        return $this->aliasesLibrary->get($rootNode->getIdentifier());
    }

    private function reinitializeState() {
        $this->queryBuilder = $this->manager->createQueryBuilder();
        $this->aliasesLibrary = new ParameterBag();
        $this->parametersLibrary = new ParameterBag();
    }

    /**
     * Validate the export_options => expr array
     * @param array $exprs
     * @return array validatedExpr
     */
    private function validateExpr(array $exprs) {
        $resolver = new OptionsResolver();

        $resolver->setDefaults([
            'comparison' => [],
            'arithmetic' => [],
            'function' => [],
        ]);

        $resolver->setAllowedTypes('comparison', 'array');
        $resolver->setAllowedTypes('arithmetic', 'array');
        $resolver->setAllowedTypes('function', 'array');

        $resolver->setAllowedValues('comparison', function(array $comparisonOptions) {
            $comparisonResolver = new OptionsResolver();

            $comparisonResolver->setDefined([
                'eq',
                'neq',
                'lt',
                'lte',
                'gt',
                'gte',
                //'isNull',
                //'isNotNull'
            ]);

            $comparisonResolver->setAllowedTypes('eq', ['int', 'float', 'double', 'string']);
            $comparisonResolver->setAllowedTypes('neq', ['int', 'string']);
            $comparisonResolver->setAllowedTypes('lt', ['int', 'float', 'double']);
            $comparisonResolver->setAllowedTypes('lte', ['int', 'float', 'double']);
            $comparisonResolver->setAllowedTypes('gt', ['int', 'float', 'double']);
            $comparisonResolver->setAllowedTypes('gte', ['int', 'float', 'double']);
            //$comparisonResolver->setAllowedTypes('isNull', 'string');
            //$comparisonResolver->setAllowedTypes('isNotNull', 'string');

            $comparisonResolver->resolve($comparisonOptions);
            return true;
        });


        $resolver->setAllowedValues('arithmetic', function(array $arithmeticOptions) {
            $arithmeticResolver = new OptionsResolver();

            $arithmeticResolver->setDefined([
                'prod',
                'diff',
                'sum',
                'quot'
            ]);

            $arithmeticResolver->setAllowedTypes('prod', ['int', 'float', 'double']);
            $arithmeticResolver->setAllowedTypes('diff', ['int', 'float', 'double']);
            $arithmeticResolver->setAllowedTypes('sum', ['int', 'float', 'double']);
            $arithmeticResolver->setAllowedTypes('quot', ['int', 'float', 'double']);

            $arithmeticResolver->resolve($arithmeticOptions);
            return true;
        });

        $resolver->setAllowedValues('function', function(array $functionOptions) {
            $functionResolver = new OptionsResolver();

            $functionResolver->setDefined([
                'like',
                'notLike',
                'between',
                'trim',
                'concat',
                'substring',
                'lower',
                'upper',
                'length',
                'avg',
                'max',
                'min',
                'abs',
                'sqrt',
                'count',
                'countDistinct',
            ]);


            $functionResolver->setAllowedTypes('like', 'string');
            $functionResolver->setAllowedTypes('notLike', 'string');
            $functionResolver->setAllowedTypes('between', 'array');
            $functionResolver->setAllowedTypes('trim', 'null');
            $functionResolver->setAllowedTypes('concat', ['string', 'int']);
            $functionResolver->setAllowedTypes('substring', 'array');
            $functionResolver->setAllowedTypes('lower', 'null');
            $functionResolver->setAllowedTypes('upper', 'null');
            $functionResolver->setAllowedTypes('length', 'null');
            $functionResolver->setAllowedTypes('avg', 'null');
            $functionResolver->setAllowedTypes('max', 'null');
            $functionResolver->setAllowedTypes('min', 'null');
            $functionResolver->setAllowedTypes('abs', 'null');
            $functionResolver->setAllowedTypes('sqrt', 'null');
            $functionResolver->setAllowedTypes('count', 'null');
            $functionResolver->setAllowedTypes('countDistinct', 'null');

            $functionResolver->setAllowedValues('between', function(array $value) {
                return count($value) == 2 && (is_int($value[0]) || is_double($value[0])  || is_float($value[0])) && (is_int($value[1]) || is_double($value[1]) || is_float($value[1]));
            });

            $functionResolver->setAllowedValues('substring', function(array $value) {
                return count($value) == 2 && is_int($value[0]) && is_int($value[1]);
            });

            $functionResolver->resolve($functionOptions);
            return true;
        });

        return $resolver->resolve($exprs);
    }

    private function buildPropertyExpression($property, array $exprs) {
        $exprs = $this->validateExpr($exprs);

        foreach ($exprs['comparison'] as $key => $value) {
            switch($key) {
                case 'eq':
                case 'neq':
                case 'lt':
                case 'lte':
                case 'gt':
                case 'gte':
                    $this->queryBuilder->andWhere(call_user_func([$this->queryBuilder->expr(), $key], $property, $this->getParameterKey($value)));
                    break;
            };
        }

        foreach ($exprs['arithmetic'] as $key => $value) {
            switch($key) {
                case 'prod':
                case 'diff':
                case 'sum':
                case 'quot':
                    $this->queryBuilder->andWhere(call_user_func([$this->queryBuilder->expr(), $key], $property, $this->getParameterKey($value)));
                    break;
            };
        }

        foreach ($exprs['function'] as $key => $value) {
            switch($key) {
                case 'like':
                case 'notLike':
                case 'concat':
                    $this->queryBuilder->andWhere(call_user_func([$this->queryBuilder->expr(), $key], $property, $this->getParameterKey($value)));
                    break;
                case 'between':
                case 'substring':
                    $this->queryBuilder->andWhere(call_user_func([$this->queryBuilder->expr(), $key], $property, $this->getParameterKey($value[0]), $this->getParameterKey($value[1])));
                    break;
                case 'trim':
                case 'lower':
                case 'upper':
                case 'length':
                case 'avg':
                case 'max':
                case 'min':
                case 'abs':
                case 'sqrt':
                case 'count':
                case 'countDistinct':
                    $this->queryBuilder->andWhere(call_user_func([$this->queryBuilder->expr(), $key], $property));
                    break;
            };
        }
    }
}