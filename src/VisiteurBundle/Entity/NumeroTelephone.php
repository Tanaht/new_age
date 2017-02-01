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
     * @Assert\Regex(
     *     pattern     = "/\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d| 2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]| 4[987654310]|3[9643210]|2[70]|7|1)\d{1,14}$/i",
     *     htmlPattern = "\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d| 2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]| 4[987654310]|3[9643210]|2[70]|7|1)\d{1,14}$"
     * )
     * @ORM\Column(name="numero", type="string", length=255)
     */
    private $numero;

    /**
     * @var int $user
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur",inversedBy="num_list")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


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
     * Set user
     *
     * @param \UserBundle\Entity\Utilisateur $user
     *
     * @return NumeroTelephone
     */
    public function setUser(Utilisateur $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\Utilisateur
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Constructeur de classe
     */
    function __construct($numero)
    {
        $this->numero = $numero;
    }

    /**
     * Affichage du numero de téléphone
     * FORMAT : +xx x xx xx xx xx
     */
    function __toString(){
        return $this->getNumero();
    }
}
