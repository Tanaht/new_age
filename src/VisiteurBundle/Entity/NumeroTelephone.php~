<?php

namespace VisiteurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\Utilisateur;

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
     *
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
     * FORMAT : 02 03 04 05 06
     */
    function toString(){
        $string_tel = preg_replace('/\s+/', '', $this->numero);
        $res_tel="";
        for ($i=0; $i < 5; $i++) { 
            $res_tel.= substr($string_tel,$i*2,2)." ";
        }
        return $res_tel;
    }
}
