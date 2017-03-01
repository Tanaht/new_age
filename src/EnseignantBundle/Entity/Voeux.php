<?php

namespace EnseignantBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Voeux
 *
 * @ORM\Table(name="voeux")
 * @ORM\Entity(repositoryClass="EnseignantBundle\Repository\VoeuxRepository")
 */
class Voeux
{
    public function __construct()
    {
        $this->cours = new ArrayCollection();
    }

    /**
     * @var int $user
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur",inversedBy="voeux")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="EnseignantBundle\Entity\Cours", cascade={"persist"})
     */
    private $cours;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Get user
     *
     * @return User
     */
    public function getUser(){
        return $this->user;
    }

    public function setUser(User $user){
        $this->user = $user;
        return $this;
    }

    /**
     * Get cours
     *
     * @return
     */
    public function setCours(Cours $cours)
    {
        $this->cours = $cours;
        return $this;
    }

    public function getCours()
    {
        return $this->cours;
    }

    public function addCours(Cours $cours){
        $this->cours[] = $cours;
        return $this;
    }
    public function removeCours(Cours $cours){
        $this->cours->removeElement($cours);
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
}

