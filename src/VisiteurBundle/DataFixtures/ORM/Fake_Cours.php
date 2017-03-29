<?php
namespace VisiteurBundle\DataFixtures;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use VisiteurBundle\Entity\Cours;

/**
 * Création des cours pour le développement
 */
class Fake_Cours implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $cours_ACO = new ArrayCollection();
        $cours_ACO->add(array(
            'type' => 'CM',
            'nbgroupe' => 1,
            'nbheure' => 20
        ));
        $cours_ACO->add(array(
            'type' => 'TD',
            'nbgroupe' => 2,
            'nbheure' => 25
        ));
        $cours_ACO->add(array(
            'type' => 'TP',
            'nbgroupe' => 4,
            'nbheure' => 15
        ));
        $cours_ACO->forAll(function($index, array $info) use($manager) {
            $cours_ACO = new Cours();
            $cours_ACO->setType($info['type']);
            $cours_ACO->setNbGroupe($info['nbgroupe']);
            $cours_ACO->setNbHeure($info['nbheure']);
            $em = $manager->getRepository("VisiteurBundle:UE");
            $ue = $em->findOneBy(array("name"=>"ACO"));
            $cours_ACO->setUe($ue);
            $manager->persist($cours_ACO);
            return true;
        });

        $cours_ACF = new ArrayCollection();
        $cours_ACF->add(array(
            'type' => 'CM',
            'nbgroupe' => 1,
            'nbheure' => 15
        ));
        $cours_ACF->add(array(
            'type' => 'TD',
            'nbgroupe' => 2,
            'nbheure' => 20
        ));
        $cours_ACF->add(array(
            'type' => 'TP',
            'nbgroupe' => 4,
            'nbheure' => 20
        ));        
        $cours_ACF->forAll(function($index, array $info) use($manager) {
            $cours_ACF = new Cours();
            $cours_ACF->setType($info['type']);
            $cours_ACF->setNbGroupe($info['nbgroupe']);
            $cours_ACF->setNbHeure($info['nbheure']);
            $em = $manager->getRepository("VisiteurBundle:UE");
            $ue = $em->findOneBy(array("name"=>"ACF"));
            $cours_ACF->setUe($ue);
            $manager->persist($cours_ACF);
            return true;
        });

        $cours_COMP = new ArrayCollection();
        $cours_COMP->add(array(
            'type' => 'CM',
            'nbgroupe' => 1,
            'nbheure' => 8
        ));
        $cours_COMP->add(array(
            'type' => 'TD',
            'nbgroupe' => 2,
            'nbheure' => 22
        ));
        $cours_COMP->add(array(
            'type' => 'TP',
            'nbgroupe' => 4,
            'nbheure' => 22
        ));        
        $cours_COMP->forAll(function($index, array $info) use($manager) {
            $cours_COMP = new Cours();
            $cours_COMP->setType($info['type']);
            $cours_COMP->setNbGroupe($info['nbgroupe']);
            $cours_COMP->setNbHeure($info['nbheure']);
            $em = $manager->getRepository("VisiteurBundle:UE");
            $ue = $em->findOneBy(array("name"=>"COMP"));
            $cours_COMP->setUe($ue);
            $manager->persist($cours_COMP);
            return true;
        }); 

        $cours_MFDS = new ArrayCollection();
        $cours_MFDS->add(array(
            'type' => 'CM',
            'nbgroupe' => 1,
            'nbheure' => 12
        ));
        $cours_MFDS->add(array(
            'type' => 'TD',
            'nbgroupe' => 2,
            'nbheure' => 18
        ));
        $cours_MFDS->add(array(
            'type' => 'TP',
            'nbgroupe' => 4,
            'nbheure' => 22
        ));        
        $cours_MFDS->forAll(function($index, array $info) use($manager) {
            $cours_MFDS = new Cours();
            $cours_MFDS->setType($info['type']);
            $cours_MFDS->setNbGroupe($info['nbgroupe']);
            $cours_MFDS->setNbHeure($info['nbheure']);
            $em = $manager->getRepository("VisiteurBundle:UE");
            $ue = $em->findOneBy(array("name"=>"MFDS"));
            $cours_MFDS->setUe($ue);
            $manager->persist($cours_MFDS);
            return true;
        });

        $cours_AOC = new ArrayCollection();
        $cours_AOC->add(array(
            'type' => 'CM',
            'nbgroupe' => 1,
            'nbheure' => 12
        ));
        $cours_AOC->add(array(
            'type' => 'TD',
            'nbgroupe' => 2,
            'nbheure' => 18
        ));
        $cours_AOC->add(array(
            'type' => 'TP',
            'nbgroupe' => 4,
            'nbheure' => 22
        ));        
        $cours_AOC->forAll(function($index, array $info) use($manager) {
            $cours_AOC = new Cours();
            $cours_AOC->setType($info['type']);
            $cours_AOC->setNbGroupe($info['nbgroupe']);
            $cours_AOC->setNbHeure($info['nbheure']);
            $em = $manager->getRepository("VisiteurBundle:UE");
            $ue = $em->findOneBy(array("name"=>"AOC"));
            $cours_AOC->setUe($ue);
            $manager->persist($cours_AOC);
            return true;
        });

        $cours_RSIP = new ArrayCollection();
        $cours_RSIP->add(array(
            'type' => 'CM',
            'nbgroupe' => 1,
            'nbheure' => 12
        ));
        $cours_RSIP->add(array(
            'type' => 'TD',
            'nbgroupe' => 2,
            'nbheure' => 18
        ));
        $cours_RSIP->add(array(
            'type' => 'TP',
            'nbgroupe' => 4,
            'nbheure' => 22
        ));        
        $cours_RSIP->forAll(function($index, array $info) use($manager) {
            $cours_RSIP = new Cours();
            $cours_RSIP->setType($info['type']);
            $cours_RSIP->setNbGroupe($info['nbgroupe']);
            $cours_RSIP->setNbHeure($info['nbheure']);
            $em = $manager->getRepository("VisiteurBundle:UE");
            $ue = $em->findOneBy(array("name"=>"RSIP"));
            $cours_RSIP->setUe($ue);
            $manager->persist($cours_RSIP);
            return true;
        });

        $cours_ANGLAIS = new ArrayCollection();
        $cours_ANGLAIS->add(array(
            'type' => 'TD',
            'nbgroupe' => 4,
            'nbheure' => 26
        ));       
        $cours_ANGLAIS->forAll(function($index, array $info) use($manager) {
            $cours_ANGLAIS = new Cours();
            $cours_ANGLAIS->setType($info['type']);
            $cours_ANGLAIS->setNbGroupe($info['nbgroupe']);
            $cours_ANGLAIS->setNbHeure($info['nbheure']);
            $em = $manager->getRepository("VisiteurBundle:UE");
            $ue = $em->findOneBy(array("name"=>"ANGLAIS"));
            $cours_ANGLAIS->setUe($ue);
            $manager->persist($cours_ANGLAIS);
            return true;
        });

        $cours_ECO = new ArrayCollection();
        $cours_ECO->add(array(
            'type' => 'CM',
            'nbgroupe' => 1,
            'nbheure' => 12
        ));
        $cours_ECO->add(array(
            'type' => 'TD',
            'nbgroupe' => 2,
            'nbheure' => 18
        ));
        $cours_ECO->add(array(
            'type' => 'TP',
            'nbgroupe' => 4,
            'nbheure' => 22
        ));        
        $cours_ECO->forAll(function($index, array $info) use($manager) {
            $cours_ECO = new Cours();
            $cours_ECO->setType($info['type']);
            $cours_ECO->setNbGroupe($info['nbgroupe']);
            $cours_ECO->setNbHeure($info['nbheure']);
            $em = $manager->getRepository("VisiteurBundle:UE");
            $ue = $em->findOneBy(array("name"=>"ECO"));
            $cours_ECO->setUe($ue);
            $manager->persist($cours_ECO);
            return true;
        });


        $manager->flush();
    }
}