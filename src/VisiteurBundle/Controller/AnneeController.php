<?php

namespace VisiteurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller qui gère la gestion des profils utilisateurs
 */
class AnneeController extends Controller
{
    public function etatAnneeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo =  $em->getRepository("VisiteurBundle:AnneeUniversitaire");
        $annees = $repo->findAll();


        return $this->render("@Visiteur/Default/etat_annee.html.twig", ["annees" => $annees]);
    }
}