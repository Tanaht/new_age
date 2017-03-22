<?php

namespace VisiteurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Missions
 *
 * @ORM\Table(name="missions")
 * @ORM\Entity(repositoryClass="VisiteurBundle\Repository\MissionsRepository")
 */
class Missions
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
     * @ORM\OneToMany(targetEntity="VisiteurBundle\Entity\Voeux", mappedBy="mission")
     *
     */
    private $voeux;

    /**
      * Une mission a un postulant
      * @ORM\OneToOne(targetEntity="UserBundle\Entity\Utilisateur")
      */
     private $candidat;

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
     * Constructor
     */
    public function __construct()
    {
        $this->voeux = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add voeux
     *
     * @param \UserBundle\Entity\Voeux $voeux
     *
     * @return Missions
     */
    public function addVoeux(\UserBundle\Entity\Voeux $voeux)
    {
        $this->voeux[] = $voeux;

        return $this;
    }

    /**
     * Remove voeux
     *
     * @param \UserBundle\Entity\Voeux $voeux
     */
    public function removeVoeux(\UserBundle\Entity\Voeux $voeux)
    {
        $this->voeux->removeElement($voeux);
    }

    /**
     * Get voeux
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVoeux()
    {
        return $this->voeux;
    }

    /**
     * Set candidat
     *
     * @param \VisiteurBundle\Entity\Utilisateur $candidat
     *
     * @return Missions
     */
    public function setCandidat(\VisiteurBundle\Entity\Utilisateur $candidat = null)
    {
        $this->candidat = $candidat;

        return $this;
    }

    /**
     * Get candidat
     *
     * @return \VisiteurBundle\Entity\Utilisateur
     */
    public function getCandidat()
    {
        return $this->candidat;
    }
}
