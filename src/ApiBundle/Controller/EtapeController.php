<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use UserBundle\Entity\Utilisateur;
use VisiteurBundle\Entity\Etape;
use VisiteurBundle\Entity\UE;
use VisiteurBundle\Entity\Voeux;

class EtapeController extends FOSRestController
{
    public function getEtapesAction()
    {
        $etapes = $this->getDoctrine()->getRepository(Etape::class)->getEtapesFromUser($this->getUser());

        $view = $this->view($etapes, 200);
        return $this->handleView($view);
    }

    /**
     * @Get("/etape/{id}", requirements={"id":"\d+"})
     */
    public function getEtapeAction(Etape $etape)
    {
        $view = $this->view($etape, 200);
        return $this->handleView($view);
    }
}