<?php

namespace VisiteurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Form\ProfilGeneralInformationsType;
use UserBundle\Form\UtilisateurType;
use VisiteurBundle\Entity\Email;
use VisiteurBundle\Entity\NumeroTelephone;

/**
 * Controller qui gère la gestion des profils utilisateurs
 */
class ProfilController extends Controller
{
    public function monProfilAction(Request $request)
    {
        $utilisateur = $this->getUser();
        $form = $this->createForm(ProfilGeneralInformationsType::class, $utilisateur, ['action' => $this->generateUrl('visiteur_homepage')]);

        if($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $em->persist($utilisateur);
                $utilisateur->getEmailList()->forAll(function($index,Email $email) use($em,$utilisateur)  {
                    $email->setUser($utilisateur);
                    $em->persist($email);
                    return true;
                });
                $utilisateur->getNumList()->forAll(function($index,NumeroTelephone $numero) use($em,$utilisateur)  {
                    $numero->setUser($utilisateur);
                    $em->persist($numero);
                    return true;
                });
                $em->flush();
                return $this->redirect($request->getUri());
            }
        }

        return $this->render("@Visiteur/Default/mon_profil.html.twig", ['profilGeneralInformationsForm' => $form->createView()]);
    }
}