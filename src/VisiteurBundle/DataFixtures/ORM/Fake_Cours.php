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
        $cours1 = new ArrayCollection();
        $cours2 = new ArrayCollection();

        $cours1->add(array(
            'type' => 'CM',
            'nbgroupe' => 1,
            'nbheure' => 20
        ));

        $cours1->add(array(
            'type' => 'TD',
            'nbgroupe' => 2,
            'nbheure' => 25
        ));

        $cours1->add(array(
            'type' => 'TP',
            'nbgroupe' => 4,
            'nbheure' => 15
        ));
        $cours2->add(array(
            'type' => 'CM',
            'nbgroupe' => 1,
            'nbheure' => 15
        ));

        $cours2->add(array(
            'type' => 'TD',
            'nbgroupe' => 2,
            'nbheure' => 20
        ));

        $cours2->add(array(
            'type' => 'TP',
            'nbgroupe' => 4,
            'nbheure' => 20
        ));



        $cours1->forAll(function($index, array $info) use($manager) {
            $cours1 = new Cours();
            $cours1->setType($info['type']);
            $cours1->setNbGroupe($info['nbgroupe']);
            $cours1->setNbHeure($info['nbheure']);
            $em = $manager->getRepository("VisiteurBundle:UE");
            $ue = $em->findOneBy(array("name"=>"ACO"));
            $cours1->setUe($ue);
            $manager->persist($cours1);
            return true;
        });
        $cours2->forAll(function($index, array $info) use($manager) {
            $cours2 = new Cours();
            $cours2->setType($info['type']);
            $cours2->setNbGroupe($info['nbgroupe']);
            $cours2->setNbHeure($info['nbheure']);
            $em = $manager->getRepository("VisiteurBundle:UE");
            $ue = $em->findOneBy(array("name"=>"ACF"));
            $cours2->setUe($ue);
            $manager->persist($cours2);
            return true;
        });
        $manager->flush();
    }
}