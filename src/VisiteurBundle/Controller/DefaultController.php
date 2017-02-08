<?php

namespace VisiteurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use VisiteurBundle\Form\RechercheUtilisateurForm;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('VisiteurBundle:Default:index.html.twig');
    }

    public function consulter_profilsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = null;
        $username = null;
        $form = $this->createForm(RechercheUtilisateurForm::class, $username);
        if($form->isSubmitted() && $form->isValid()) {
            dump($user);
           # $user = $em->getRepository('UserBundle:Utilisateur')->findOneBy(['username'=>$username]);
        }
        return $this->render('VisiteurBundle:Default:profils.html.twig', ["user"=>$user, "rechercherUtilisateurForm"=>$form->createView()]);
    }
}
