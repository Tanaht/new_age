<?php

namespace VisiteurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use UserBundle\Entity\Utilisateur;

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

        /** @var Utilisateur $associatedUtilisateur */
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
     * @var Mission
     * @ORM\ManyToOne(targetEntity="Mission",inversedBy="voeux")
     */
    private $mission;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text")
     */
    private $commentaire;


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

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Voeux
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set mission
     *
     * @param \VisiteurBundle\Entity\Mission $mission
     *
     * @return Voeux
     */
    public function setMission(\VisiteurBundle\Entity\Mission $mission = null)
    {
        $this->mission = $mission;

        return $this;
    }

    /**
     * Get mission
     *
     * @return \VisiteurBundle\Entity\Mission
     */
    public function getMission()
    {
        return $this->mission;
    }
}
