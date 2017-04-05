<?php
namespace VisiteurBundle\DataFixtures;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use VisiteurBundle\Entity\Voeux;

/**
 * Création des voeux pour le développement
 */
class Fake_Voeux implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $voeux = new ArrayCollection();
        $voeux->add(array("nb_heures"=>20, 'commentaire' => 'un comment'));
        $voeux->forAll(function($index, array $info) use($manager) {
            $voeu = new Voeux();
            $voeu->setNbHeures($info['nb_heures']);
            $voeu->setCommentaire($info['commentaire']);
            $em = $manager->getRepository("VisiteurBundle:UE");
            $ue = $em->findOneBy(array("name"=>"ACO"));
            $em = $manager->getRepository("VisiteurBundle:Cours");
            $cours = $em->findOneBy(array("ue"=>$ue,
                                          "type"=>"CM"));
            $voeu->setCours($cours);
            $em = $manager->getRepository("UserBundle:Utilisateur");
            $user = $em->findOneBy(array("username"=>"antmu"));
            $voeu->setUtilisateur($user);
            $manager->persist($voeu);
            return true;
        });

        $voeux2 = new ArrayCollection();
        $voeux2->add(array("nb_heures"=>20, 'commentaire' => 'Lorem ipsum'));
        $voeux2->forAll(function($index, array $info) use($manager) {
            $voeu = new Voeux();
            $voeu->setNbHeures($info['nb_heures']);
            $voeu->setCommentaire($info['commentaire']);
            $em = $manager->getRepository("VisiteurBundle:UE");
            $ue = $em->findOneBy(array("name"=>"MFDS"));
            $em = $manager->getRepository("VisiteurBundle:Cours");
            $cours = $em->findOneBy(array("ue"=>$ue,
                "type"=>"TD"));
            $voeu->setCours($cours);
            $em = $manager->getRepository("UserBundle:Utilisateur");
            $user = $em->findOneBy(array("username"=>"antmu"));
            $voeu->setUtilisateur($user);
            $manager->persist($voeu);
            return true;
        });

        $voeux3 = new ArrayCollection();
        $voeux3->add(array("nb_heures"=>20, 'commentaire' => 'Lorem ipsum dolor sit'));
        $voeux3->forAll(function($index, array $info) use($manager) {
            $voeu = new Voeux();
            $voeu->setNbHeures($info['nb_heures']);
            $voeu->setCommentaire($info['commentaire']);
            $em = $manager->getRepository("VisiteurBundle:UE");
            $ue = $em->findOneBy(array("name"=>"ACO"));
            $em = $manager->getRepository("VisiteurBundle:Cours");
            $cours = $em->findOneBy(array("ue"=>$ue,
                "type"=>"TD"));
            $voeu->setCours($cours);
            $em = $manager->getRepository("UserBundle:Utilisateur");
            $user = $em->findOneBy(array("username"=>"tanaky"));
            $voeu->setUtilisateur($user);
            $manager->persist($voeu);
            return true;
        });

        $manager->flush();
    }
}