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
        //TODO: how it works: http://symfony.com/doc/master/bundles/FOSRestBundle/2-the-view-layer.html
        $users = $this->getDoctrine()->getRepository(Utilisateur::class)->getUsernames();
        $view = $this->view($users, 200);
        return $this->handleView($view);
    }

    /**
     * Retourne les infos de l'utilisateur connecté
     * route : get_profil
     * url  : [GET] /profil/
     */
    public function getProfilAction(){
        $user = $this->getUser();
	    if(!is_object($user)){
	      throw $this->createNotFoundException();
	    }
        $view = $this->view($user);
        return $this->handleView($view);
    }

    /**
     * Modifie l'utilisateur connecté avec les données transmises dans la requete PUT
     * route : put_profil
     * url	 : [PUT] /profil/{slug}
     */
    public function putProfilAction($slug){

    }
}
