<?php
namespace VisiteurBundle\DataFixtures;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use VisiteurBundle\Entity\UE;

/**
 * Création des ues pour le développement
 */
class Fake_ue implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $ues = new ArrayCollection();

        $ues->add(array(
            'name' => 'ACO'
        ));

        $ues->add(array(
            'name' => 'ACF'
        ));

        $ues->forAll(function($index, array $info) use($manager) {
            $ue = new ue();
            $ue->setName($info['name']);
            $ue->setEtapes($info['etapes']);

            
            $manager->persist($ue);
            return true;
        });
        $manager->flush();
    }
}