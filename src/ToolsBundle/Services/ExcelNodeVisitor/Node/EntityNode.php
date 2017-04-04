<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 04/04/2017
 * Time: 17:41
 */

namespace ToolsBundle\Services\ExcelNodeVisitor\Node;


use ToolsBundle\Services\ExcelNodeVisitor\Visitor\AbstractNodeVisitor;

class EntityNode extends AbstractComponent
{

    /**
     * @param AbstractNodeVisitor $visitor
     */
    public function accept(AbstractNodeVisitor $visitor)
    {
        $visitor->visitEntityNode($this);
    }
}