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
        usort($annees, function ($a, $b)
        {
            if ($a->getAnneeInt() == $b->getAnneeInt()) {
                return 0;
            }
            return ($a->getAnneeInt() < $b->getAnneeInt()) ? 1 : -1;
        });

        return $this->render("@Visiteur/Default/etat_annee.html.twig", ["annees" => $annees]);
    }

}