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
    //Attribut type a ajouter après sa creation

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
}
