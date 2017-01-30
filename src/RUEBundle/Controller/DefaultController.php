<?php

namespace RUEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('RUEBundle:Default:index.html.twig');
    }
}
