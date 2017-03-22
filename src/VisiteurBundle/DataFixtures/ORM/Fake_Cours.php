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

        $manager->flush();
    }
}