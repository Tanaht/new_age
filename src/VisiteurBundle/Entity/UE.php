<?php

namespace VisiteurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var int $responsable
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur",inversedBy="ue_list")
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
        $this->etapes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add etape
     *
     * @param \VisiteurBundle\Entity\Etape $etape
     *
     * @return UE
     */
    public function addEtape(\VisiteurBundle\Entity\Etape $etape)
    {
        $this->etapes[] = $etape;

        return $this;
    }

    /**
     * Remove etape
     *
     * @param \VisiteurBundle\Entity\Etape $etape
     */
    public function removeEtape(\VisiteurBundle\Entity\Etape $etape)
    {
        $this->etapes->removeElement($etape);
    }

    /**
     * Get etapes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEtapes()
    {
        return $this->etapes;
    }

    /**
     * Set responsable
     *
     * @param \UserBundle\Entity\Utilisateur $responsable
     *
     * @return UE
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
