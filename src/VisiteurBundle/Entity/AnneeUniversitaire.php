<?php

namespace VisiteurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


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
     * @ORM\Column(name="annee_scolaire", type="string", length=255, unique=true)
     */
    private $anneeScolaire;

    /**
     * @var ArrayCollection etat_list
     *
     * @ORM\OneToMany(targetEntity="VisiteurBundle\Entity\EtatAnnee", mappedBy="id_annee", cascade={"persist"})
     */
    private $etat_list;

    /**
     * AnneeUniversitaire constructor.
     * @param ArrayCollection $email_list
     */
    public function __construct()
    {
        $this->etat_list = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getEtatList()
    {
        return $this->etat_list;
    }

    /**
     * @param ArrayCollection $etat_list
     */
    public function setEtatList($etat_list)
    {
        $this->etat_list = $etat_list;
    }

    /**
     * Remove etat
     *
     * @param \VisiteurBundle\Entity\EtatAnnee $etat
     */
    public function removeEmailList(EtatAnnee $etat)
    {
        $this->etat_list->removeElement($etat);
    }

    /**
     * Ajoute un etat à l'année
     * @pre : $etat n'est pas null
     *
     * @param \VisiteurBundle\Entity\EtatAnnee $etat
     *
     * @return AnneeUniversitaire
     */
    public function addEmailList(Email $etat)
    {
        if(!is_null($etat)){
            $this->etat_list[] = $etat;
            $etat->setIdAnnee($this);
        }
        return $this;
    }

}
