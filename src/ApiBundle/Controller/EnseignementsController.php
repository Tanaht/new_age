<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use UserBundle\Entity\Utilisateur;
use VisiteurBundle\Entity\Etape;

class EnseignementsController extends FOSRestController
{
    public function getEnseignementsAction()
    {
        $enseignements = $this->getDoctrine()->getRepository(Etape::class)->getNames();

        $view = $this->view($enseignements, 200);
        return $this->handleView($view);
    }
}