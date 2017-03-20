<?php

namespace VisiteurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use  VisiteurBundle\Entity\Notifications;
use Symfony\Component\HttpFoundation\Request;
/**
 * Controller qui gère la gestion des profils utilisateurs
 */
class NotificationsController extends Controller
{
    static function compDateTime($a,$b)
    {
        $a1 = ($a[0]->getDatetime())->getTimestamp();
        $b1 = ($b[0]->getDatetime())->getTimestamp();

        if ($a1 == $b1) {
            return 0;
        }
        return ($a1 < $b1) ? 1 : -1;
    }
    public function notificationsAction(Request $request)
    {

        $page = $request->query->get('page');
        $em = $this->getDoctrine()->getManager();
        $utilisateur = $this->getUser();
        $idUtil = $utilisateur->getId();
        $utilNotifList = $em->getRepository('VisiteurBundle:UtilNotif')->findby(['util' => $idUtil]);
        $notifList = array();


        foreach ($utilNotifList as $utilNotif) {
            $idNotif = $utilNotif->getNotif();
            $notif = $em->getRepository('VisiteurBundle:Notifications')->findby(['id' => $idNotif]);
            $notif[1] = $utilNotif->getLu();
            array_push($notifList, $notif);
        }
        usort($notifList,array($this, "compDateTime"));

        $notifs = array();
        $date = date_format($notifList[0][0]->getDatetime(),  'd-m-Y');
        $notifJour = array();
        foreach ($notifList as $notif) {

            $dateNot = date_format($notif[0]->getDatetime(),  'd-m-Y');

            if ($dateNot != $date) {
                array_push($notifs, $notifJour);
                $notifJour = array();
            }
            array_push($notifJour, $notif);

            $date = date_format($notif[0]->getDatetime(),  'd-m-Y');

        }

        array_push($notifs, $notifJour);

        return $this->render("@Visiteur/Default/notifications.html.twig", ["notifs" => $notifs]);

    }

}