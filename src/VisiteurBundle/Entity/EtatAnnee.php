<?php

namespace VisiteurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use VisiteurBundle\Entity\AnneeUniversitaire;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * EtatAnnee
 *
 * @ORM\Table(name="etat_annee")
 * @ORM\Entity(repositoryClass="VisiteurBundle\Repository\EtatAnneeRepository")
 */
class EtatAnnee
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
     * @ORM\Column(name="ordre", type="integer")
     */
    private $ordre;



    /**
     * @var string
     *
     * @ORM\Column(name="intitule", type="string", length=255)
     */
    private $intitule;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=500)
     */
    private $description;


    /**
     * @var string
     *
     * @ORM\Column(name="mois_fin", type="string", length=255)
     */
    private $mois_fin;



    /**
     * @var string
     *
     * @ORM\Column(name="mois_debut", type="string", length=255)
     */
    private $mois_debut;


    /**
     * @var boolean
     * @ORM\Column(name="en_cours", type="boolean", options={"default":false})
     */
    private $encours;



    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="VisiteurBundle\Entity\AnneeUniversitaire", inversedBy="etat_list")
     * @ORM\JoinColumn(name="id_annee", referencedColumnName="id")
     */
    private $id_annee;


    /**
     * Constructor
     */
    public function __construct()
    {

        $this->setEncours(false);
    }



    /**
     * @return int
     */
    public function getIdAnnee()
    {
        return $this->id_annee;
    }

    /**
     * @param int $idAnnee
     */
    public function setIdAnnee($id_annee)
    {
        $this->id_annee = $id_annee;
    }



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
     * Set intitule
     *
     * @param string $intitule
     *
     * @return EtatAnnee
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule
     *
     * @return string
     */
    public function getIntitule()
    {
        return $this->intitule;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return EtatAnnee
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
     * @return string
     */
    public function getMoisFin()
    {
        return $this->mois_fin;
    }

    /**
     * @param string $mois_fin
     */
    public function setMoisFin($mois_fin)
    {
        $this->mois_fin = $mois_fin;
    }

    /**
     * @return string
     */
    public function getMoisDebut()
    {
        return $this->mois_debut;
    }

    /**
     * @param string $mois_debut
     */
    public function setMoisDebut($mois_debut)
    {
        $this->mois_debut = $mois_debut;
    }


    /**
     * @return int
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * @param int $ordre
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
    }

    /**
     * @return encours
     */
    public function getEncours()
    {
        return $this->encours;
    }

    /**
     * @param boolean $encours
     */
    public function setEncours($encours)
    {
        $this->encours = $encours;
    }



}

