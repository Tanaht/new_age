<?php

namespace RCBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('RCBundle:Default:index.html.twig');
    }
}
