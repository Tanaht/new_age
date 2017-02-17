<?php

namespace VisiteurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\Utilisateur;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * NumeroTelephone
 *
 * @ORM\Table(name="numero_telephone")
 * @ORM\Entity(repositoryClass="VisiteurBundle\Repository\NumeroTelephoneRepository")
 */
class NumeroTelephone
{
    function __construct()
    {
        //A constructor Entity serve only to initialize Collections in Doctrine
    }

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
     * @ORM\Column(name="numero", type="string", length=255, nullable=true)
     */
    private $numero;

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
     * Set numero
     *
     * @param string $numero
     *
     * @return NumeroTelephone
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Affichage du numero de téléphone
     * FORMAT : +xx x xx xx xx xx
     */
    function __toString(){
        return $this->getNumero();
    }
}
