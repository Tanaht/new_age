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
use UserBundle\Entity\Utilisateur;
use VisiteurBundle\Entity\Notification;
use VisiteurBundle\Entity\UtilNotif;

class ServiceAlert
{

    /**
     * @var EntityManager
     */
    public $em;

    /**
     * @var null|Utilisateur
     */
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
        if($this->user !== null)
            return $this->em->getRepository(UtilNotif::class)->getNbNotifNonLu($this->user);

        return 0;
    }


    public function getNotifNonLu(){
        if($this->user !== null)
            return $this->em->getRepository(UtilNotif::class)->getNotifNonLu($this->user, 5);

        return [];
    }

}