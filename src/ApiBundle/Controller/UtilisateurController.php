<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use UserBundle\Entity\Utilisateur;

class UtilisateurController extends FOSRestController
{
    public function getUtilisateursAction()
    {
        //TODO: how it works: http://symfony.com/doc/master/bundles/FOSRestBundle/2-the-view-layer.html
        $users = $this->getDoctrine()->getRepository(Utilisateur::class)->findAll();

        $view = $this->view($users, 200);
        return $this->handleView($view);
    }
}
