<?php

namespace VisiteurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('VisiteurBundle:Default:index.html.twig');
    }
}
