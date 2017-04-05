<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use UserBundle\Entity\Utilisateur;
use IntervenantBundle\Entity\Mission;

class MissionController extends FOSRestController
{
    public function getMissionsAction()
    {
        $missions = $this->getDoctrine()->getRepository(Mission::class)->getMissionsFromUser($this->getUser());

        $view = $this->view($missions, 200);
        return $this->handleView($view);
    }

    /**
     * @Get("/etape/{id}", requirements={"id":"\d+"})
     */
    public function getMissionAction(Mission $mission)
    {
        $view = $this->view($missions, 200);
        return $this->handleView($view);
    }
}