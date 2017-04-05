<?php

namespace IntervenantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mission
 *
 * @ORM\Table(name="mission")
 * @ORM\Entity(repositoryClass="IntervenantBundle\Repository\MissionRepository")
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
      * Une mission a plusieurs postulant
      * @ORM\ManyToMany(targetEntity="UserBundle\Entity\Utilisateur",mappedBy="missions_postulees",cascade={"persist"})
      */
    private $candidats;


    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->voeux = new \Doctrine\Common\Collections\ArrayCollection();
        $this->candidats = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add voeux
     *
     * @param \VisiteurBundle\Entity\Voeux $voeux
     *
     * @return Mission
     */
    public function addVoeux(\VisiteurBundle\Entity\Voeux $voeux)
    {
        $this->voeux[] = $voeux;

        return $this;
    }

    /**
     * Remove voeux
     *
     * @param \VisiteurBundle\Entity\Voeux $voeux
     */
    public function removeVoeux(\VisiteurBundle\Entity\Voeux $voeux)
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
     * Add candidat
     *
     * @param \UserBundle\Entity\Utilisateur $candidat
     *
     * @return Mission
     */
    public function addCandidat(\UserBundle\Entity\Utilisateur $candidat)
    {
        $this->candidats[] = $candidat;

        return $this;
    }

    /**
     * Remove candidat
     *
     * @param \UserBundle\Entity\Utilisateur $candidat
     */
    public function removeCandidat(\UserBundle\Entity\Utilisateur $candidat)
    {
        $this->candidats->removeElement($candidat);
    }

    /**
     * Get candidats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCandidats()
    {
        return $this->candidats;
    }
}
