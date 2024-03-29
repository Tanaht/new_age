<?php

namespace IntervenantBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Mission
 *
 * @ORM\Table(name="mission")
 * @ORM\Entity(repositoryClass="IntervenantBundle\Repository\MissionRepository")
 */
class Mission
{
    const STATUT_LIBRE = 'LIBRE';
    const STATUT_ARCHIVEE = 'ARCHIVEE';
    const STATUT_FERMEE = 'FERMEE';

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
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="VisiteurBundle\Entity\Voeux", mappedBy="mission", cascade={"persist"})
     *
     */
    private $voeux;

    /**
      * Une mission a plusieurs postulant
      * @ORM\ManyToMany(targetEntity="UserBundle\Entity\Utilisateur",mappedBy="missions_postulees",cascade={"persist"})
      */
    private $candidats;

    /**
      * L'intervenant qui va réellement effectué la mission
      * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur",inversedBy="missions_effectuees",cascade={"persist"})
      */
    private $intervenant;

    /**
     * Une mission a un statut permettant ou non aux candidats de postuler
     * @ORM\Column(type="string", columnDefinition="ENUM('ARCHIVEE', 'LIBRE', 'FERMEE')")
     */
    private $statut;

    /**
     * Une mission est rattachée à une composante
     * @ORM\ManyToOne(targetEntity="VisiteurBundle\Entity\Composante",inversedBy="missions",cascade={"persist"})
     */
    private $composante;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->voeux = new ArrayCollection();
        $this->candidats = new ArrayCollection();
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

        if($voeux->getMission() !== $this)
            $voeux->setMission($this);

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

        if($voeux->getMission() === $this)
            $voeux->setMission();
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
        $candidat->addMissionsPostulee($this);
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
        $candidat->removeMissionsPostulee($this);
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

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Mission
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set intervenant
     *
     * @param \UserBundle\Entity\Utilisateur $intervenant
     *
     * @return Mission
     */
    public function setIntervenant(\UserBundle\Entity\Utilisateur $intervenant = null)
    {
        $this->intervenant = $intervenant;
        $intervenant->addMissionsEffectuee($this);
        return $this;
    }

    /**
     * Get intervenant
     *
     * @return \UserBundle\Entity\Utilisateur
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Mission
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set composante
     *
     * @param \VisiteurBundle\Entity\Composante $composante
     *
     * @return Mission
     */
    public function setComposante(\VisiteurBundle\Entity\Composante $composante = null)
    {
        $this->composante = $composante;
        $composante->addMission($this);
        return $this;
    }

    /**
     * Get composante
     *
     * @return \VisiteurBundle\Entity\Composante
     */
    public function getComposante()
    {
        return $this->composante;
    }
}
