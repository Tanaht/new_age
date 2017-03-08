<?php

namespace VisiteurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use VisiteurBundle\Form\RechercheUtilisateurForm;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('VisiteurBundle:Default:index.html.twig');
    }

    public function consulterProfilsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = null;
        $form = $this->createForm(RechercheUtilisateurForm::class, null, ['attr' => ['action' => $this->generateUrl('visiteur_profils')]]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $id = $form->getData()['identifiant'];
            dump($id);
            $user = $em->getRepository('UserBundle:Utilisateur')->findOneBy(['id' => $id]);
            dump($user);
        }

        return $this->render('VisiteurBundle:Default:profils.html.twig', ["user"=>$user, "rechercherUtilisateurForm"=>$form->createView()]);
    }
}
