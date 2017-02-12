<?php

namespace VisiteurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
        $this->user_list = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add userList
     *
     * @param \UserBundle\Entity\Utilisateur $userList
     *
     * @return Composante
     */
    public function addUserList(\UserBundle\Entity\Utilisateur $userList)
    {
        $this->user_list[] = $userList;

        return $this;
    }

    /**
     * Remove userList
     *
     * @param \UserBundle\Entity\Utilisateur $userList
     */
    public function removeUserList(\UserBundle\Entity\Utilisateur $userList)
    {
        $this->user_list->removeElement($userList);
    }

    /**
     * Get userList
     *
     * @return \Doctrine\Common\Collections\Collection
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
        foreach ($this->user_list as $user){
            $user->eraseCredentials(true);
        }
    }
}
