<?php

namespace VisiteurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller qui gère la gestion des profils utilisateurs
 */
class ProfilController extends Controller
{
    public function monProfilAction()
    {
        return $this->render("@Visiteur/Default/mon_profil.html.twig");
    }
}