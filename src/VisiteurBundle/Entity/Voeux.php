<?php

namespace VisiteurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Voeux
 *
 * @ORM\Table(name="voeux")
 * @ORM\Entity(repositoryClass="VisiteurBundle\Repository\VoeuxRepository")
 */
class Voeux
{

    /**
     * @Assert\Callback
     * Validator Callback used to restrict the numbers of Voeux for a same Utilisateur on the same Cours
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        $voeu = $context->getObject();
        $associatedUtilisateur = $voeu->getUtilisateur();


        $noViolations = $associatedUtilisateur->getVoeuxList()->forAll(function($key, Voeux $v) use($voeu) {
            if($v === $voeu)
                return true;

            if($v->getCours() === $voeu->getCours()) {
                return false;
            }

            return true;
        });

        if(!$noViolations) {
            $context->buildViolation("Ce voeu ne peux pas être enregistré pour l'utilisateur :utilisateur Un voeu existe déjà pour le cours: :cours", [
                ':utilisateur' => $associatedUtilisateur->getPrenom() . " " . $associatedUtilisateur->getNom(),
                ':cours' => $voeu->getCours()->getUe()->getName() . " [" . $voeu->getCours()->getType() . "]"
            ])->addViolation();
        }


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
     *@ORM\ManyToOne(targetEntity="Cours", inversedBy="voeux")
     */
    private $cours;

    /**
     * @ORM\Column(name="nbHeures", type="integer")
     */
    private $nbHeures;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Utilisateur",inversedBy="voeux_list")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="VisiteurBundle\Entity\Missions",inversedBy="voeux")
     *
     */
    private $mission;

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

    /**
     * Set nbHeures
     *
     * @param integer $nbHeures
     *
     * @return Voeux
     */
    public function setNbHeures($nbHeures)
    {
        $this->nbHeures = $nbHeures;

        return $this;
    }

    /**
     * Get nbHeures
     *
     * @return integer
     */
    public function getNbHeures()
    {
        return $this->nbHeures;
    }
}
