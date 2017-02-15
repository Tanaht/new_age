<?php

namespace EnseignantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VoeuxController extends Controller
{
    public function indexAction()
    {
        return $this->render('EnseignantBundle:Default:index.html.twig');
    }

    public function bilanVoeuxParComposanteAction()
    {
        $composante = $this->getUser()->getComposante();
        $users = $this->getDoctrine()->getManager()->getRepository('UserBundle:Utilisateur')->findBy(['composante' => $composante]);
        return $this->render('EnseignantBundle:Voeux:bilanParComposante.html.twig', ["users"=>$users]);
    }
}
