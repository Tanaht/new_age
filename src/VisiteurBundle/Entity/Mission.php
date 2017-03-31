<?php

namespace VisiteurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\Utilisateur;

/**
 * Missions
 *
 * @ORM\Table(name="mission")
 * @ORM\Entity(repositoryClass="VisiteurBundle\Repository\MissionRepository")
 */
class Mission
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
     * @param Voeux $voeux
     *
     * @return Mission
     */
    public function addVoeux(Voeux $voeux)
    {
        $this->voeux[] = $voeux;

        return $this;
    }

    /**
     * Remove voeux
     *
     * @param Voeux $voeux
     */
    public function removeVoeux(Voeux $voeux)
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
     * @param Utilisateur $candidat
     *
     * @return Mission
     */
    public function setCandidat(Utilisateur $candidat = null)
    {
        $this->candidat = $candidat;

        return $this;
    }

    /**
     * Get candidat
     *
     * @return Utilisateur
     */
    public function getCandidat()
    {
        return $this->candidat;
    }
}
