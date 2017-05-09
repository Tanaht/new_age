<?php

namespace VisiteurBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\Utilisateur;

/**
 * UE
 *
 * @ORM\Table(name="u_e")
 * @ORM\Entity(repositoryClass="VisiteurBundle\Repository\UERepository")
 */
class UE
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Etape", mappedBy="ues")
     */
    private $etapes;

    /**
     * @var Utilisateur $responsable
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur",inversedBy="ue_list")
     */
    private $responsable;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Cours", mappedBy="ue")
     */
    private $cours;


    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enService", type="boolean")
     */
    private $enService = false;

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
     * Set name
     *
     * @param string $name
     *
     * @return UE
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
     * Constructor
     */
    public function __construct()
    {
        $this->etapes = new ArrayCollection();
        $this->cours = new ArrayCollection();
    }

    /**
     * Add etape
     *
     * @param Etape $etape
     *
     * @return UE
     */
    public function addEtape(Etape $etape)
    {
        $this->etapes[] = $etape;

        if(!$etape->getUes()->contains($this))
            $etape->addUe($this);
        return $this;
    }

    /**
     * Remove etape
     *
     * @param Etape $etape
     */
    public function removeEtape(Etape $etape)
    {
        $this->etapes->removeElement($etape);

        if($etape->getUes()->contains($this))
            $etape->removeUe($this);
    }

    /**
     * Get etapes
     *
     * @return Collection
     */
    public function getEtapes()
    {
        return $this->etapes;
    }

    /**
     * Set responsable
     *
     * @param Utilisateur $responsable
     *
     * @return UE
     */
    public function setResponsable(Utilisateur $responsable = null)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return Utilisateur
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Add cour
     *
     * @param Cours $cour
     *
     * @return UE
     */
    public function addCour(Cours $cour)
    {
        $this->cours[] = $cour;

        if($cour->getUe() !== $this)
            $cour->setUe($this);

        return $this;
    }

    /**
     * Remove cour
     *
     * @param Cours $cour
     */
    public function removeCour(Cours $cour)
    {
        $this->cours->removeElement($cour);

        $cour->setUe();
    }

    /**
     * Get cours
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCours()
    {
        return $this->cours;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return UE
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set enService
     *
     * @param boolean $enService
     *
     * @return UE
     */
    public function setEnService($enService)
    {
        $this->enService = $enService;

        return $this;
    }

    /**
     * Get enService
     *
     * @return boolean
     */
    public function getEnService()
    {
        return $this->enService;
    }
}
