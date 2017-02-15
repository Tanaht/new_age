<?php
/**
 * Created by PhpStorm.
 * User: tanna
 * Date: 15/02/2017
 * Time: 15:31
 */

namespace UserBundle\Form;


use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class CollectionType extends \Symfony\Component\Form\Extension\Core\Type\CollectionType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['allow-add'] = $options['allow_add'] ? 'true' : 'false';
        $view->vars['attr']['allow-delete'] = $options['allow_delete'] ? 'true' : 'false';
        parent::buildView($view, $form, $options);
    }
}