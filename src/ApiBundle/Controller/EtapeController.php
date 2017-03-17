<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
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

    /**
     * Retourne les ues associé à l'étape fournis en paramètre
     * @param $id l\'identifiant d'une étape.
     * @return Response
     */
    public function getEtapeUesAction($id)
    {
        $etape = $this->getDoctrine()->getRepository(Etape::class)->find($id);
        if($etape == null)
            throw new HttpException(404, "La ressource n'existe pas");

        $view = $this->view($etape->getUes(), 200);
        return $this->handleView($view);
    }

    public function postEtapeUesAction($id) {
        $request = $this->get('request_stack')->getCurrentRequest();
        dump($request->get('datas'));

        $this->view([], 200);
    }
}