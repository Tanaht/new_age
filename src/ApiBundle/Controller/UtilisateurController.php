<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use UserBundle\Entity\Utilisateur;

class UtilisateurController extends FOSRestController
{
	/**
     * Retourne les infos de tous les utilisateurs de la plateforme
     * route : get_utilisateurs
     * url  : [GET] /utilisateurs/
     */
    public function getUtilisateursAction()
    {
        $users = $this->getDoctrine()->getRepository(Utilisateur::class)->getUsers();
        $view = $this->view($users, 200);
        return $this->handleView($view);
    }

    /**
     * Retourne les infos de l'utilisateur connecté
     * route : get_profil
     * url  : [GET] /profil/
     */
    public function getProfilAction()
    {

        $user = $this->getUser();
        if (!(is_object($user) && get_class($user) == Utilisateur::class)) {
            throw $this->createNotFoundException();
        }
        $view = $this->view($user);
        return $this->handleView($view);
    }
}
