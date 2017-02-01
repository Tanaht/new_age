<?php

namespace VisiteurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller pour l'affichage des enseignements
 */
class ListeEnseignementsController extends Controller
{
    public function listeEnseignementsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $etapes = $em->getRepository('VisiteurBundle:Etape')->findAll();
        return $this->render("@Visiteur/Default/liste_enseignements.html.twig",
            array('etapes' => $etapes,));
    }
}