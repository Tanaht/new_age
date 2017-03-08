<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use UserBundle\Entity\Utilisateur;
use VisiteurBundle\Entity\Etape;

class EtapeController extends FOSRestController
{
    public function getEtapeAction()
    {
        $etapes = $this->getDoctrine()->getRepository(Etape::class)->getNames();

        $view = $this->view($enseignements, 200);
        return $this->handleView($view);
    }
}