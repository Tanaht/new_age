<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 04/04/2017
 * Time: 17:42
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;


use Doctrine\ORM\EntityManager;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\AbstractNodeVisitor;

/**
 * Le Noeud principal des données qui vont être importé/exporté:
 * En mode export, le noeud à le même fonctionnement qu'un CollectionNode,
 * En mode import, c'est le noeud propriétaire des données qui vont être importé
 * Class RootNode
 * @package ToolsBundle\Services\ExcelNodeVisitor\Node
 */
class RootNode extends AbstractComponent
{
    /**
     * @param AbstractNodeVisitor $visitor
     */
    public function accept(AbstractNodeVisitor $visitor)
    {
        $visitor->visitRootNode($this);
    }
}