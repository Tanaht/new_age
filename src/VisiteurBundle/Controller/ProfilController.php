<?php

namespace VisiteurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Form\UtilisateurType;

/**
 * Controller qui gère la gestion des profils utilisateurs
 */
class ProfilController extends Controller
{
    public function monProfilAction(Request $request)
    {
        $utilisateur = $this->getUser();
        dump($utilisateur);
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            dump("valid  && submitted");
        }

        return $this->render("@Visiteur/Default/mon_profil.html.twig", ['form' => $form->createView()]);
    }
}