<?php

namespace VisiteurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notifications
 *
 * @ORM\Table(name="notifications")
 * @ORM\Entity(repositoryClass="VisiteurBundle\Repository\NotificationsRepository")
 */
class Notifications
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
     * @var int
     *
     * @ORM\Column(name="importance", type="smallint")
     */
    private $importance;

    /**
     * @var int
     *
     * @ORM\Column(name="emeteur", type="integer", nullable = true)
     */
    private $emeteur;


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
     * @return Notifications
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
     * @return Notifications
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
     * Set emeteur
     *
     * @param integer $emeteur
     *
     * @return Notifications
     */
    public function setEmeteur($emeteur)
    {
        $this->emeteur = $emeteur;

        return $this;
    }

    /**
     * Get emeteur
     *
     * @return int
     */
    public function getEmeteur()
    {
        return $this->emeteur;
    }
}

