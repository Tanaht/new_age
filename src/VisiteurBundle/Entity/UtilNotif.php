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
     * @ORM\JoinColumn(nullable =false)
     */
    private $util;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="VisiteurBundle\Entity\Notifications")
     * @ORM\JoinColumn(nullable =false)
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
     * Set idUtil
     *
     * @param integer $idUtil
     *
     * @return UtilNotif
     */
    public function setIdUtil($idUtil)
    {
        $this->idUtil = $idUtil;

        return $this;
    }

    /**
     * Get idUtil
     *
     * @return int
     */
    public function getIdUtil()
    {
        return $this->idUtil;
    }

    /**
     * Set idNotif
     *
     * @param integer $idNotif
     *
     * @return UtilNotif
     */
    public function setIdNotif($idNotif)
    {
        $this->idNotif = $idNotif;

        return $this;
    }

    /**
     * Get idNotif
     *
     * @return int
     */
    public function getIdNotif()
    {
        return $this->idNotif;
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

