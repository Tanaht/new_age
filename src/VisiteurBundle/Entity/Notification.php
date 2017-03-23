<?php

namespace VisiteurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;
use UserBundle\Entity\Utilisateur;

/**
 * Notifications
 *
 * @ORM\Table(name="notifications")
 * @ORM\Entity(repositoryClass="VisiteurBundle\Repository\NotificationRepository")
 */
class Notification
{
    const URGENT = 3;
    const IMPORTANT = 1;
    const NORMAL = 0;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean
     * @ORM\Column(type="boolean" )
     */
    private $nouvelle = true;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=700)
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime")
     */
    private $datetime;

    /**
     * @var Utilisateur
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur", inversedBy="notifications")
     */
    private $recepteur;

    /**
     * @var int
     *
     * @ORM\Column(name="importance", type="smallint")
     */
    private $importance;

    /**
     * @var Utilisateur
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur")
     */
    private $emetteur;

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
     * Set text
     *
     * @param string $text
     *
     * @return Notification
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }



    /**
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param \DateTime $datetime
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
    }



    /**
     * Set importance
     *
     * @param integer $importance
     *
     * @return Notification
     */
    public function setImportance($importance)
    {
        $this->importance = $importance;

        return $this;
    }

    /**
     * Get importance
     *
     * @return int
     */
    public function getImportance()
    {
        return $this->importance;
    }

    /**
     * Set recepteur
     *
     * @param \UserBundle\Entity\Utilisateur $recepteur
     *
     * @return Notification
     */
    public function setRecepteur(\UserBundle\Entity\Utilisateur $recepteur = null)
    {
        $this->recepteur = $recepteur;

        return $this;
    }

    /**
     * Get recepteur
     *
     * @return \UserBundle\Entity\Utilisateur
     */
    public function getRecepteur()
    {
        return $this->recepteur;
    }

    /**
     * Set emetteur
     *
     * @param \UserBundle\Entity\Utilisateur $emetteur
     *
     * @return Notification
     */
    public function setEmetteur(\UserBundle\Entity\Utilisateur $emetteur = null)
    {
        $this->emetteur = $emetteur;

        return $this;
    }

    /**
     * Get emetteur
     *
     * @return \UserBundle\Entity\Utilisateur
     */
    public function getEmetteur()
    {
        return $this->emetteur;
    }

    /**
     * Set nouvelle
     *
     * @param boolean $nouvelle
     *
     * @return Notification
     */
    public function setNouvelle($nouvelle)
    {
        $this->nouvelle = $nouvelle;

        return $this;
    }

    /**
     * Get nouvelle
     *
     * @return boolean
     */
    public function getNouvelle()
    {
        return $this->nouvelle;
    }
}
