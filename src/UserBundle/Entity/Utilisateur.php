<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use VisiteurBundle\Entity\Composante;
use VisiteurBundle\Entity\Email;
use VisiteurBundle\Entity\NumeroTelephone;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UtilisateurRepository")
 */
class Utilisateur implements UserInterface, ContainerAwareInterface, \Serializable
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->email_list = new ArrayCollection();
        $this->num_list = new ArrayCollection();
    }


    /**
     * @var Container
     */
    private $container;

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
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * One User have Many Emails.
     * @ORM\ManyToMany(targetEntity="VisiteurBundle\Entity\Email", cascade={"persist"})
     * @ORM\JoinTable(name="utilisateurs_emails",
     *      joinColumns={@ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="email_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $email_list;

    /**
     * One User have Many Phonenumbers.
     * @ORM\ManyToMany(targetEntity="VisiteurBundle\Entity\NumeroTelephone", cascade={"persist"})
     * @ORM\JoinTable(name="utilisateurs_numerosTelephones",
     *      joinColumns={@ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="phonenumber_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $num_list;


    /**
     * @var string
     * @Assert\Url(
     *     message = "L'url {{ value }} est invalide !",
     *     protocols = {"http", "https"},
     *     checkDNS = true,
     *     dnsMessage = "L'hôte {{ value }} est introuvable.",
     *     groups={"general_information"}
     * )
     * @ORM\Column(name="site_web", type="string", length=255, nullable=true)
     */
    private $site_web;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text",nullable=true)
     */
    private $description;

    /**
     * @var Composante
     *
     * @ORM\ManyToOne(targetEntity="VisiteurBundle\Entity\Composante",inversedBy="user_list")
     * @ORM\JoinColumn(name="composante_id", referencedColumnName="id")
     */
    private $composante;

    /**
     * @var string $bureau : Emplacement de bureau
     *
     * @ORM\Column(name="bureau", type="text",nullable=true)
     */
    private $bureau;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $image;

    /**
     * TODO: add some File constraints like: minWidth, maxWidth, minHeight, maxHeight
     * @Assert\NotBlank(message="Merci d'enregistrer une image.", groups={"image"})
     * @Assert\File(mimeTypes={ "image/jpeg" }, groups={"image"})
     */
    private $file;

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
    }


    /**
     * @var ArrayCollection $ue_list
     *
     * Liste des ue dont l'utilisateur est responsable
     *
     * @ORM\OneToMany(targetEntity="VisiteurBundle\Entity\UE", mappedBy="responsable", cascade={"persist"})
     */
    private $ue_list;

    /**
     * @var ArrayCollection $etape_list
     *
     * Liste des etapes dont l'utilisateur est responsable
     *
     * @ORM\OneToMany(targetEntity="VisiteurBundle\Entity\Etape", mappedBy="responsable", cascade={"persist"})
     */
    private $etape_list;

    /**
     * @ORM\OneToMany(targetEntity="VisiteurBundle\Entity\Voeux", mappedBy="utilisateur")
     */
    private $voeux_list;

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
     * Set username
     *
     * @param string $username
     *
     * @return Utilisateur
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Utilisateur
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Utilisateur
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Utilisateur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return ["ROLE_USER"];//TODO: modifier plus tard en fonction de la variable $role_actuel
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials($visited = false)
    {
        // TODO: Implement eraseCredentials() method.
        // $this->password = null;
        // if(!$visited){
        //     $this->composante->eraseCredentials();
        // }
        
    }

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->nom,
            $this->prenom,
            $this->email_list
        ));
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->nom,
            $this->prenom,
            $this->email_list
            ) = unserialize($serialized);
    }

    /**
     * Remove emailList
     *
     * @param Email $emailList
     */
    public function removeEmailList(Email $emailList)
    {
        $this->email_list->removeElement($emailList);
    }

    /**
     * Get emailList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmailList()
    {
        return $this->email_list;
    }

    /**
     * Ajoute un email à l'utilisateur
     *
     * precondition  : $emailList n'est pas null
     *
     * @param \VisiteurBundle\Entity\Email $email
     * 
     * @return Utilisateur
     */
    public function addEmailList(Email $emailList)
    {
        $this->email_list->add($emailList);
        return $this;
    }

    /**
     * Ajoute un numéro de téléphone à l'utilisateur
     *
     * precondition : $numList n'est pas null
     *
     * @param \VisiteurBundle\Entity\NumeroTelephone $numList
     *
     * @return Utilisateur
     */
    public function addNumList(NumeroTelephone $numList)
    {
        $this->num_list->add($numList);
        return $this;
    }

    /**
     * Remove numList
     *
     * @param \VisiteurBundle\Entity\NumeroTelephone $numList
     */
    public function removeNumList(NumeroTelephone $numList)
    {
        $this->num_list->removeElement($numList);
    }

    /**
     * Get numList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNumList()
    {
        return $this->num_list;
    }

    /**
     * Set siteWeb
     *
     * @param string $siteWeb
     *
     * @return Utilisateur
     */
    public function setSiteWeb($siteWeb)
    {
        $this->site_web = $siteWeb;

        return $this;
    }

    /**
     * Get siteWeb
     *
     * @return string
     */
    public function getSiteWeb()
    {
        return $this->site_web;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Utilisateur
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set composante
     *
     * @param Composante $composante
     *
     * @return Utilisateur
     */
    public function setComposante(Composante $composante = null)
    {
        $composante->addUserList($this);
        $this->composante = $composante;

        return $this;
    }

    /**
     * Get Composante
     *
     * @return Composante
     */
    public function getComposante()
    {
        return $this->composante;
    }

    /**
     * Set bureau
     *
     * @param string $bureau
     *
     * @return Utilisateur
     */
    public function setBureau($bureau)
    {
        $this->bureau = $bureau;

        return $this;
    }

    /**
     * Get bureau
     *
     * @return string
     */
    public function getBureau()
    {
        return $this->bureau;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Utilisateur
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add ueList
     *
     * @param \VisiteurBundle\Entity\UE $ueList
     *
     * @return Utilisateur
     */
    public function addUeList(\VisiteurBundle\Entity\UE $ueList)
    {
        $this->ue_list[] = $ueList;

        return $this;
    }

    /**
     * Remove ueList
     *
     * @param \VisiteurBundle\Entity\UE $ueList
     */
    public function removeUeList(\VisiteurBundle\Entity\UE $ueList)
    {
        $this->ue_list->removeElement($ueList);
    }

    /**
     * Get ueList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUeList()
    {
        return $this->ue_list;
    }

    /**
     * Add etapeList
     *
     * @param \VisiteurBundle\Entity\Etape $etapeList
     *
     * @return Utilisateur
     */
    public function addEtapeList(\VisiteurBundle\Entity\Etape $etapeList)
    {
        $this->etape_list[] = $etapeList;

        return $this;
    }

    /**
     * Remove etapeList
     *
     * @param \VisiteurBundle\Entity\Etape $etapeList
     */
    public function removeEtapeList(\VisiteurBundle\Entity\Etape $etapeList)
    {
        $this->etape_list->removeElement($etapeList);
    }

    /**
     * Get etapeList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEtapeList()
    {
        return $this->etape_list;
    }

    /**
     * Add voeuxList
     *
     * @param \VisiteurBundle\Entity\Voeux $voeuxList
     *
     * @return Utilisateur
     */
    public function addVoeuxList(\VisiteurBundle\Entity\Voeux $voeuxList)
    {
        $this->voeux_list[] = $voeuxList;

        return $this;
    }

    /**
     * Remove voeuxList
     *
     * @param \VisiteurBundle\Entity\Voeux $voeuxList
     */
    public function removeVoeuxList(\VisiteurBundle\Entity\Voeux $voeuxList)
    {
        $this->voeux_list->removeElement($voeuxList);
    }

    /**
     * Get voeuxList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVoeuxList()
    {
        return $this->voeux_list;
    }
}
