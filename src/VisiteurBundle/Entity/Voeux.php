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
}
