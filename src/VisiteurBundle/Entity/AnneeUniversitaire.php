<?php

namespace VisiteurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnneeUniversitaire
 *
 * @ORM\Table(name="annee_universitaire")
 * @ORM\Entity(repositoryClass="VisiteurBundle\Repository\AnneeUniversitaireRepository")
 */
class AnneeUniversitaire
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
     * @ORM\Column(name="annee_scolaire", type="string", length=255)
     */
    private $anneeScolaire;

    /**
     * @var enum
     *
     * @ORM\Column(name="etat", type="string", columnDefinition="enum('O','I','V','S','C','CL')")
     */
    private $etat;

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
     * Set anneeScolaire
     *
     * @param string $anneeScolaire
     *
     * @return AnneeUniversitaire
     */
    public function setAnneeScolaire($anneeScolaire)
    {
        $this->anneeScolaire = $anneeScolaire;

        return $this;
    }

    /**
     * Get anneeScolaire
     *
     * @return string
     */
    public function getAnneeScolaire()
    {
        return $this->anneeScolaire;
    }

    /**
     * Get etat
     *
     * @return enum
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set etat
     *
     * @param enum $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }


}
