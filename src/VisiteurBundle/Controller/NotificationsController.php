<?php

namespace VisiteurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\DateTime;
use  VisiteurBundle\Entity\Notifications;
use Symfony\Component\HttpFoundation\Request;
use VisiteurBundle\Form\NotificationForm;

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
    public function notificationsAction(Request $request, $mois, $annee)
    {


        /*    $month = date('m');
            $year = date('Y');*/


            $jour = "01/".$mois."/".$annee." 00:00";



        $date_url = (\DateTime::createFromFormat("d/m/Y H:i",$jour));

        $date_url2 = (\DateTime::createFromFormat("d/m/Y H:i",$jour))->modify('+1 month');

        $manager =  $this->getDoctrine()->getManager();

        $em = $this->getDoctrine()->getManager();
        $utilisateur = $this->getUser();
        $idUtil = $utilisateur->getId();
        $utilNotifList = $em->getRepository('VisiteurBundle:UtilNotif')->findby(['util' => $idUtil]);
        $notifList = array();
        $date_query = new \Datetime('now');


        $query = $manager
            ->createQuery("SELECT n, u.lu 
                          FROM VisiteurBundle:Notifications n, VisiteurBundle:UtilNotif u
                          WHERE u.notif = n.id
                          AND u.util = :user 
                          AND n.datetime >= :dateDeb
                          AND n.datetime < :dateFin
                          ORDER BY n.datetime DESC")
            ->setParameter("dateDeb", $date_url)
            ->setParameter("dateFin", $date_url2)
            ->setParameter("user", $idUtil);
        $notifList = $query->getResult();

/*
        $date_query = new \Datetime('now');
        $date_query = $date_query->modify('-1 month');

        foreach ($utilNotifList as $utilNotif) {
            $idNotif = $utilNotif->getNotif();
            $notif = $em->getRepository('VisiteurBundle:Notifications')->findby(['id' => $idNotif]);
            $query = $manager
                ->createQuery("SELECT n FROM VisiteurBundle:Notifications n WHERE n.id=:idParam AND MONTH(n.datetime)=3")
                ->setParameter('idParam', $idNotif );

            $notif = $query->getResult();
            $notif[1] = $utilNotif->getLu();
            $utilNotif->setLu(0);

            $manager->persist($utilNotif);

            array_push($notifList, $notif);
        }

        if(empty($notifList)){

        } else {
            usort($notifList,array($this, "compDateTime"));
        }


*/
        $notifs = array();
        $notifJour = array();
       if(!empty($notifList)){

            $date = date_format($notifList[0][0]->getDatetime(),  'd-m-Y');


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
       }

        $manager->flush();

        return $this->render("@Visiteur/Default/notifications.html.twig", [
            "notifs" => $notifs, "month"=>$mois, "year"=>$annee
        ]);

    }

}