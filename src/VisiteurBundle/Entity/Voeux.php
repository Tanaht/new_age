<?php

namespace VisiteurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Voeux
 *
 * @ORM\Table(name="voeux")
 * @ORM\Entity(repositoryClass="VisiteurBundle\Repository\VoeuxRepository")
 */
class Voeux
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
     *@ORM\ManyToOne(targetEntity="Cours", inversedBy="voeux")
     */
    private $cours;

    /**
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\Utilisateur",inversedBy="voeux_list")
     * @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id", nullable=true)
     *
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Missions",inversedBy="voeux")
     *
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
     * Set cours
     *
     * @param \VisiteurBundle\Entity\Cours $cours
     *
     * @return Voeux
     */
    public function setCours(\VisiteurBundle\Entity\Cours $cours = null)
    {
        $this->cours = $cours;

        return $this;
    }

    /**
     * Get cours
     *
     * @return \VisiteurBundle\Entity\Cours
     */
    public function getCours()
    {
        return $this->cours;
    }

    /**
     * Set utilisateur
     *
     * @param \UserBundle\Entity\Utilisateur $utilisateur
     *
     * @return Voeux
     */
    public function setUtilisateur(\UserBundle\Entity\Utilisateur $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \UserBundle\Entity\Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }
}
