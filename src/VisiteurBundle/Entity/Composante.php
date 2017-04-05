<?php

namespace VisiteurBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\Utilisateur;

/**
 * Composante
 *
 * @ORM\Table(name="composante")
 * @ORM\Entity(repositoryClass="VisiteurBundle\Repository\ComposanteRepository")
 */
class Composante
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var ArrayCollection $user_list
     *
     * Liste des utilisateurs de la composante
     *
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\Utilisateur", mappedBy="composante", cascade={"persist"})
     */
    private $user_list;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="VisiteurBundle\Entity\Etape", mappedBy="composante")
     */
    private $etapes;

    /**
     * Liste des missions de la composante
     * @ORM\OneToMany(targetEntity="IntervenantBundle\Entity\Mission", mappedBy="composante", cascade={"persist"})
     */
    private $missions;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return Composante
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user_list = new ArrayCollection();
    }

    /**
     * Add userList
     *
     * @param Utilisateur $userList
     *
     * @return Composante
     */
    public function addUserList(Utilisateur $userList)
    {
        $this->user_list[] = $userList;

        return $this;
    }

    /**
     * Remove userList
     *
     * @param Utilisateur $userList
     */
    public function removeUserList(Utilisateur $userList)
    {
        $this->user_list->removeElement($userList);
    }

    /**
     * Get userList
     *
     * @return Collection
     */
    public function getUserList()
    {
        return $this->user_list;
    }

    /**
     * Removes sensitive data from the composante.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(){
        // TODO: Implement eraseCredentials() method.

        $this->user_list->forAll(function($index, Utilisateur $utilisateur) {
            $utilisateur->eraseCredentials(true);
            return true;
        });
    }

    /**
     * Add etape
     *
     * @param \VisiteurBundle\Entity\Etape $etape
     *
     * @return Composante
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
     * Add mission
     *
     * @param \IntervenantBundle\Entity\Mission $mission
     *
     * @return Composante
     */
    public function addMission(\IntervenantBundle\Entity\Mission $mission)
    {
        $this->missions[] = $mission;

        return $this;
    }

    /**
     * Remove mission
     *
     * @param \IntervenantBundle\Entity\Mission $mission
     */
    public function removeMission(\IntervenantBundle\Entity\Mission $mission)
    {
        $this->missions->removeElement($mission);
    }

    /**
     * Get missions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMissions()
    {
        return $this->missions;
    }
}
