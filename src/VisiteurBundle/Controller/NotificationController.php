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

    public function mesNotificationsAction(Request $request, $mois, $annee, $version)
    {

        $om = $this->getDoctrine()->getManager();
        $utilisateur = $this->getUser();

        //$mois and $annee are never undefined see: VisiteurBundle\Resources\config\routing.yml
        $selectedDate = "01/".$mois."/".$annee." 00:00";

        
        $format = $this->getParameter('date_time_format');
        $date_debut = DateTime::createFromFormat($format ,$selectedDate);
        $date_fin = DateTime::createFromFormat($format ,$selectedDate)->modify('+1 month');

        $notificationsParJour = new ParameterBag();
        $notifs = [];
        if($version == "v1") {
            $notifList = $om->getRepository(UtilNotif::class)->getNotifications($utilisateur, $date_debut, $date_fin);

            dump($notifList->count(), $notifList);
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
            if(!$notifList->isEmpty()){
                dump($notifList);
                $date = date_format($notifList[0][0]->getDatetime(),  'd-m-Y');


                foreach ($notifList as $notif) {

                    $dateNot = date_format($notif[0]->getDatetime(),  'd-m-Y');

                    if ($dateNot != $date) {
                        array_push($notifs, $notifJour);
                        $notifJour = array();
                    }
                    array_push($notifJour, $notif);

                    $notif[0]->setNouvelle(0);
                    $om->persist($notif[0]);


                    $date = date_format($notif[0]->getDatetime(),  'd-m-Y');

                }
                array_push($notifs, $notifJour);
                $om->flush();


               dump($notifList);
            }
        }
        elseif ($version == "v2") {
            $notifications = $om->getRepository(Notification::class)->getNotifications($utilisateur, $date_debut);
            $notificationsParJour = new ParameterBag();

            /** @var Notification $notification*/
            foreach ($notifications as $key => $notification) {
                $bagKey = $notification->getDatetime()->format("d/m/Y");
                
                if(!$notificationsParJour->has($bagKey)) {
                    $notificationsParJour->set($bagKey, []);
                }

                $array = $notificationsParJour->get($bagKey);
                $array[] = $notification;
                $notificationsParJour->set($bagKey, $array);
            }
        }



        return $this->render("@Visiteur/Default/mesNotifications.html.twig", [
            "notifs" => $notifs,
            "notificationsParJour" => $notificationsParJour,
            "month"=>$mois,
            "year"=>$annee,
        ]);

    }

}