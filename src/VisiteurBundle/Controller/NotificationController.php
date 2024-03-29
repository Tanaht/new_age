<?php

namespace VisiteurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use VisiteurBundle\Entity\Notification;
use VisiteurBundle\Entity\UtilNotif;

/**
 * Controller qui gère la gestion des profils utilisateurs
 */
class NotificationController extends Controller
{

    public function mesNotificationsAction(Request $request, $mois, $annee)
    {

        $om = $this->getDoctrine()->getManager();
        $utilisateur = $this->getUser();

        //$mois and $annee are never undefined see: VisiteurBundle\Resources\config\routing.yml
        $selectedDate = "01/".$mois."/".$annee." 00:00";

        
        $format = $this->getParameter('date_time_format');
        $date_debut = DateTime::createFromFormat($format ,$selectedDate);
        $date_fin = DateTime::createFromFormat($format ,$selectedDate)->modify('+1 month');

        $utilNotifList = $om->getRepository(UtilNotif::class)->getUtilisateursNotifications($utilisateur, $date_debut, $date_fin);


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
        $notifs = [];
        $notifJour = [];
        if(!$utilNotifList->isEmpty()) {

            $date = date_format($utilNotifList[0]->getNotif()->getDatetime(), 'd-m-Y');


            foreach ($utilNotifList as $utilNotif) {

                $dateNot = date_format($utilNotif->getNotif()->getDatetime(), 'd-m-Y');

                if ($dateNot != $date) {
                    array_push($notifs, $notifJour);
                    $notifJour = array();
                }

                array_push($notifJour, array($utilNotif->getNotif(), "lu" => $utilNotif->getLu()));

                $utilNotif->setLu(1);
                $om->persist($utilNotif);

                $date = date_format($utilNotif->getNotif()->getDatetime(), 'd-m-Y');

            }
            array_push($notifs, $notifJour);
            $om->flush();

        }

        //$notificationsParJour = new ParameterBag();

        /** @var UtilNotif $utilNotif*/
        /*foreach ($utilNotifList as $key => $utilNotif) {
            $bagKey = $utilNotif->getNotif()->getDatetime()->format("d/m/Y");

            if(!$notificationsParJour->has($bagKey)) {
                $notificationsParJour->set($bagKey, []);
            }

            $array = $notificationsParJour->get($bagKey);
            $array[] = $utilNotif;
            $notificationsParJour->set($bagKey, $array);
        }*/

        return $this->render("@Visiteur/Default/mesNotifications.html.twig", [
            "notifs" => $notifs,
            "month"=>$mois,
            "year"=>$annee,
        ]);

    }

}