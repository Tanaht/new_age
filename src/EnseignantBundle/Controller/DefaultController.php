<?php

namespace EnseignantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EnseignantBundle:Default:index.html.twig');
    }
}
