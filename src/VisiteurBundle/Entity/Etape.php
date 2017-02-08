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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
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
     * @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     */
    private $responsable;

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
}
