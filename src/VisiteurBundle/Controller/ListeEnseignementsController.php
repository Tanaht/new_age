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
        return $this->render("@Visiteur/Default/liste_enseignements.html.twig");
    }
}