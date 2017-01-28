<?php

namespace IntervenantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('IntervenantBundle:Default:index.html.twig');
    }
}
