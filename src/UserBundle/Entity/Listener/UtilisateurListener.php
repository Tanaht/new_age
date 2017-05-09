<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 08/03/2017
 * Time: 19:08
 */

namespace UserBundle\Entity\Listener;



use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use UserBundle\Entity\Utilisateur;

class UtilisateurListener
{
    /**
     * @var TokenStorage $tokenStorage
     */
    private $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /** @ORM\PreUpdate() */
    public function preUpdateHandler(Utilisateur $utilisateur, PreUpdateEventArgs $event) {
        if($event->hasChangedField('roleActuel')) {
            $token = new UsernamePasswordToken($utilisateur, null, "main", $utilisateur->getRoles());
            $this->tokenStorage->setToken($token);
        }
    }

}