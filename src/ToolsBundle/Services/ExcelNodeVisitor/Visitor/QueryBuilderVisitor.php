<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 04/04/2017
 * Time: 19:22
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Visitor;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ToolsBundle\Services\ExcelNodeVisitor\Node\AbstractNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\CollectionNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\EntityNode;
use ToolsBundle\Services\ExcelNodeVisitor\Node\PropertyLeaf;
use ToolsBundle\Services\ExcelNodeVisitor\Node\RootNode;

class QueryBuilderVisitor extends AbstractNodeVisitor
{

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var ParameterBag
     */
    private $queryParameters;

    /**
     * @var EntityManager
     */
    private $manager;

    private $col = 0;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param RootNode $node
     * @return Query
     */
    public function getQuery(RootNode $node) {
        $this->queryParameters = new ParameterBag();
        $this->queryBuilder = $this->manager->getRepository($node->getMetadata()->getName())->createQueryBuilder($node->getIdentifier());
        $node->accept($this);

        return $this->queryBuilder->setParameters($this->queryParameters->all())->getQuery();
    }

    public function visitRootNode(RootNode $node)
    {
        dump($node . " Col: " . $this->col . " width: " . $node->getWidth() . " row: " . $node->getDepth());
        //$this->queryBuilder->addSelect($node->getIdentifier());
        foreach ($node->getProperties()->getIterator() as $childNode) {
            /** @var AbstractNode $childNode */
            $childNode->accept($this);
        }
    }

    public function visitCollectionNode(CollectionNode $node)
    {
        dump($node . " Col: " . $this->col . " width: " . $node->getWidth() . " row: " . $node->getDepth());
        $join = $node->getParent()->getIdentifier() . "." . $node->getProperty();
        $alias = $node->getIdentifier();
        $this->queryBuilder->innerJoin($join, $alias);

        foreach ($node->getProperties()->getIterator() as $childNode) {
            /** @var AbstractNode $childNode */
            $childNode->accept($this);
        }
    }

    public function visitEntityNode(EntityNode $node)
    {

        $join = $node->getParent()->getIdentifier() . "." . $node->getProperty();
        $alias = $node->getIdentifier();
        $this->queryBuilder->innerJoin($join, $alias);

        foreach ($node->getProperties()->getIterator() as $childNode) {
            /** @var AbstractNode $childNode */
            $childNode->accept($this);
        }
    }

    public function visitPropertyLeaf(PropertyLeaf $node)
    {
        dump($node . " Col: " . $this->col++ . " row: " . $node->getDepth());
        if($node->getExportOptions()->has('expr'))
            $this->buildPropertyExpression($node->getParent()->getIdentifier() . "." . $node->getProperty(), $node->getExportOptions()->get('expr'));
    }

    /**
     * @param string|integer|boolean $value
     * @return string Key of the given value
     */
    private function getParameterKey($value) {
        $key = ":key_" . $this->queryParameters->count();
        $this->queryParameters->set($key, $value);
        return $key;
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

    /**
     * Build Where clauses in function of QueryBuilder Expressions
     * @param $property
     * @param array $exprs
     */
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