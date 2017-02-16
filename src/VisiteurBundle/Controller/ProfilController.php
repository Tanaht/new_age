<?php

namespace VisiteurBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\Utilisateur;
use UserBundle\Form\ProfilDescriptionType;
use UserBundle\Form\ProfilGeneralInformationsType;
use UserBundle\Form\ProfilPasswordType;
use UserBundle\Form\UtilisateurType;
use VisiteurBundle\Entity\Email;
use VisiteurBundle\Entity\NumeroTelephone;

/**
 * Controller qui gère la gestion des profils utilisateurs
 */
class ProfilController extends Controller
{
    private function handleProfilGeneraleInformationsForm(Request $request, Form $form, Utilisateur $utilisateur, ObjectManager $om) {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $om = $this->getDoctrine()->getManager();

            $om->persist($utilisateur);
            $utilisateur->getEmailList()->forAll(function($index,Email $email) use($om,$utilisateur)  {
                $email->setUser($utilisateur);
                $om->persist($email);
                return true;
            });
            $utilisateur->getNumList()->forAll(function($index,NumeroTelephone $numero) use($om,$utilisateur)  {
                $numero->setUser($utilisateur);
                $om->persist($numero);
                return true;
            });
            $om->flush();
            return true;
        }
        return false;
    }

    private function handleProfilDescriptionForm(Request $request, Form $form, Utilisateur $utilisateur, ObjectManager $om)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $om->persist($utilisateur);
            $om->flush();
            return true;
        }
        return false;
    }

    private function handleProfiPasswordForm(Request $request, Form $form, Utilisateur $utilisateur, ObjectManager $om)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //retrieve password string in profilPasswordForm arborescence
            $newPassword = $form->get('password')->get('first')->getData();

            $encoder = $this->get('security.encoder_factory')->getEncoder($utilisateur);

            $utilisateur->setPassword($encoder->encodePassword($newPassword, null));

            $om->persist($utilisateur);
            $om->flush();
        }
        return false;
    }

    public function monProfilAction(Request $request)
    {
        $utilisateur = $this->getUser();

        $profilGeneralInformationsform = $this->createForm(ProfilGeneralInformationsType::class, $utilisateur, ['action' => $request->getUri()]);
        $profilDescriptionForm = $this->createForm(ProfilDescriptionType::class, $utilisateur, ['action' => $request->getUri()]);
        $profilPasswordForm = $this->createForm(ProfilPasswordType::class, $utilisateur, ['action' => $request->getUri()]);

        $om = $this->getDoctrine()->getManager();

        if($request->isMethod('POST')) {
            if($this->handleProfilGeneraleInformationsForm($request, $profilGeneralInformationsform, $utilisateur, $om))
                return $this->redirectToRoute("visiteur_homepage");//POST REDIRECT GET (see: https://fr.wikipedia.org/wiki/Post-redirect-get)

            if($this->handleProfilDescriptionForm($request, $profilDescriptionForm, $utilisateur, $om))
                return $this->redirectToRoute("visiteur_homepage");//POST REDIRECT GET (see: https://fr.wikipedia.org/wiki/Post-redirect-get)

            if($this->handleProfiPasswordForm($request, $profilPasswordForm, $utilisateur, $om))
                return $this->redirectToRoute("visiteur_homepage");//POST REDIRECT GET (see: https://fr.wikipedia.org/wiki/Post-redirect-get)
        }

        return $this->render("@Visiteur/Default/mon_profil.html.twig", [
            'profilGeneralInformationsForm' => $profilGeneralInformationsform->createView(),
            'profilDescriptionForm' => $profilDescriptionForm->createView(),
            'profilPasswordForm' => $profilPasswordForm->createView(),
        ]);
    }
}