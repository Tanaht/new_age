<?php

namespace EnseignantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cours
 *
 * @ORM\Table(name="cours")
 * @ORM\Entity(repositoryClass="EnseignantBundle\Repository\CoursRepository")
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
     * @ORM\Column(name="nbh", type="integer")
     */
    private $nbh;


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
     * Set nbh
     *
     * @param integer $nbh
     *
     * @return Cours
     */
    public function setNbh($nbh)
    {
        $this->nbh = $nbh;

        return $this;
    }

    /**
     * Get nbh
     *
     * @return int
     */
    public function getNbh()
    {
        return $this->nbh;
    }
}

