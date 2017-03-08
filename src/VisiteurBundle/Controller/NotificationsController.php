<?php

namespace VisiteurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller qui gère la gestion des profils utilisateurs
 */
class NotificationsController extends Controller
{
    public function notificationsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $notifs = null;


        return $this->render("@Visiteur/Default/notifications.html.twig", ["notifs" => $notifs]);
    }

}