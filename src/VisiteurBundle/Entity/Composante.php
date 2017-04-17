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
     * @ORM\OneToMany(targetEntity="VisiteurBundle\Entity\Etape", mappedBy="composante", cascade={"persist"})
     */
    private $etapes;


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
        $etape->setComposante($this);
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
        $etape->setComposante();
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
}
