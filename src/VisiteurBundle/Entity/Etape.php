<?php

namespace VisiteurBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Etape
 *
 * @ORM\Table(name="etape")
 * @ORM\Entity(repositoryClass="VisiteurBundle\Repository\EtapeRepository")
 */
class Etape
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)//TODO: Ce serai mieux unique=false nan ? si sur deux années différentes il y a la même étape ca va poser problème.
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="UE", inversedBy="etapes")
     * @ORM\JoinTable(name="etapes_ues")
     */
    private $ues;

    /**
     * @var int $responsable
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur",inversedBy="etape_list")
     */
    private $responsable;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $description;

    /**
     * @var Composante
     * @ORM\ManyToOne(targetEntity="VisiteurBundle\Entity\Composante", inversedBy="etapes")
     */
    private $composante;

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
     * @return Etape
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
        $this->ues = new ArrayCollection();
    }

    /**
     * Add ue
     *
     * @param UE $ue
     *
     * @return Etape
     */
    public function addUe(UE $ue)
    {
        $this->ues[] = $ue;
        $ue->addEtape($this);
        return $this;
    }

    /**
     * Remove ue
     *
     * @param \VisiteurBundle\Entity\UE $ue
     */
    public function removeUe(\VisiteurBundle\Entity\UE $ue)
    {
        $this->ues->removeElement($ue);
    }

    /**
     * Get ues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUes()
    {
        return $this->ues;
    }

    /**
     * Set responsable
     *
     * @param \UserBundle\Entity\Utilisateur $responsable
     *
     * @return Etape
     */
    public function setResponsable(\UserBundle\Entity\Utilisateur $responsable = null)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return \UserBundle\Entity\Utilisateur
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Etape
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
     * Set composante
     *
     * @param \VisiteurBundle\Entity\Composante $composante
     *
     * @return Etape
     */
    public function setComposante(\VisiteurBundle\Entity\Composante $composante = null)
    {
        $this->composante = $composante;

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
