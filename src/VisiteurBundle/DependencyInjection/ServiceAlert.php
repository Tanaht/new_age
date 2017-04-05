<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 29/03/17
 * Time: 17:09
 */

namespace VisiteurBundle\DependencyInjection;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use VisiteurBundle\Entity\Notification;
use VisiteurBundle\Entity\UtilNotif;

class ServiceAlert
{

    public $user;

    public function __construct( EntityManager $entityManager, TokenStorage $token) {
        $this->em = $entityManager;

        if($token->getToken()!=null){
            $this->user = $token->getToken()->getUser();
        } else {
            $this->user = null;
        }
    }

    public function getNbAlerts(){

        $result = 0;

        $utilisateur = $this->user;
        $count = $this->em->getRepository(UtilNotif::class)->getNbNotifNonLu($utilisateur);

        return $count;
    }


    public function getNotifNonLu(){

        $nbNotif = 5;

        $utilisateur = $this->user;
        $result = $this->em->getRepository(UtilNotif::class)->getNotifNonLu($utilisateur, $nbNotif);

        return $result;
    }

}