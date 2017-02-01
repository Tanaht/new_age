<?php

namespace VisiteurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('VisiteurBundle:Default:index.html.twig');
    }

    public function consulter_profilsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur = $em->getRepository('UserBundle:Utilisateur')->findAll();
        return $this->render('VisiteurBundle:Default:profils.html.twig', ["users"=>$utilisateur]);
    }
}
