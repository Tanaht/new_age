<?php

namespace VisiteurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cours
 *
 * @ORM\Table(name="cours")
 * @ORM\Entity(repositoryClass="VisiteurBundle\Repository\CoursRepository")
 */
class Cours
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
     * @var int
     *
     * @ORM\Column(name="nb_heure", type="integer")
     */
    private $nbHeure;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_groupe", type="integer")
     */
    private $nbGroupe;

    /**
     * @var string
     *
     * @ORM\Column(name="info_upplementaire", type="string")
     */
    private $infoSupplementaire;

    /**
     *@ORM\ManyToOne(targetEntity="UE", inversedBy="cours")
     */
    private $ue;

    /**
    *@ORM\Column(type="string", columnDefinition="ENUM('CM', 'TD', 'TP')")
    */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="Voeux", mappedBy="cours")
     */
    private $voeux;
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
     * Set nbHeure
     *
     * @param integer $nbHeure
     *
     * @return Cours
     */
    public function setNbHeure($nbHeure)
    {
        $this->nbHeure = $nbHeure;

        return $this;
    }

    /**
     * Get nbHeure
     *
     * @return int
     */
    public function getNbHeure()
    {
        return $this->nbHeure;
    }

    /**
     * Set nbGroupe
     *
     * @param integer $nbGroupe
     *
     * @return Cours
     */
    public function setNbGroupe($nbGroupe)
    {
        $this->nbGroupe = $nbGroupe;

        return $this;
    }

    /**
     * Get nbGroupe
     *
     * @return int
     */
    public function getNbGroupe()
    {
        return $this->nbGroupe;
    }

    /**
     * Set infoSupplementaire
     *
     * @param string $infoSupplementaire
     *
     * @return Cours
     */
    public function setInfoSupplementaire($infoSupplementaire)
    {
        $this->infoSupplementaire = $infoSupplementaire;

        return $this;
    }

    /**
     * Get infoSupplementaire
     *
     * @return string
     */
    public function getInfoSupplementaire()
    {
        return $this->infoSupplementaire;
    }

    /**
     * Set ue
     *
     * @param \VisiteurBundle\Entity\UE $ue
     *
     * @return Cours
     */
    public function setUe(\VisiteurBundle\Entity\UE $ue = null)
    {
        $this->ue = $ue;

        return $this;
    }

    /**
     * Get ue
     *
     * @return \VisiteurBundle\Entity\UE
     */
    public function getUe()
    {
        return $this->ue;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Cours
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
     * @param \VisiteurBundle\Entity\Voeux $voeux
     *
     * @return Cours
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
}
