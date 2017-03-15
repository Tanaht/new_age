<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use UserBundle\Entity\Utilisateur;
use VisiteurBundle\Entity\Etape;

class EtapeController extends FOSRestController
{
    public function getEtapesAction()
    {
        $etapes = $this->getDoctrine()->getRepository(Etape::class)->getEtapesFromUser($this->getUser());

        $view = $this->view($etapes, 200);
        return $this->handleView($view);
    }
}