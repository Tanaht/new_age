<?php

namespace VisiteurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UtilNotif
 *
 * @ORM\Table(name="util_notif")
 * @ORM\Entity(repositoryClass="VisiteurBundle\Repository\UtilNotifRepository")
 */
class UtilNotif
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur")
     */
    private $utilisateur;

    /**
     * @var Notification
     *
     * @ORM\ManyToOne(targetEntity="VisiteurBundle\Entity\Notification")
     * @ORM\JoinColumn(name="id_notif", referencedColumnName="id")
     */
    private $notif;

    /**
     * @var bool
     *
     * @ORM\Column(name="lu", type="boolean")
     */
    private $lu;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * @param int $utilisateur
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }

    /**
     * @return Notification
     */
    public function getNotif()
    {
        return $this->notif;
    }

    /**
     * @param Notification $notif
     */
    public function setNotif(Notification $notif)
    {
        $this->notif = $notif;
    }




    /**
     * Set lu
     *
     * @param boolean $lu
     *
     * @return UtilNotif
     */
    public function setLu($lu)
    {
        $this->lu = $lu;

        return $this;
    }

    /**
     * Get lu
     *
     * @return bool
     */
    public function getLu()
    {
        return $this->lu;
    }
}

