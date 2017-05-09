<?php
namespace VisiteurBundle\DataFixtures;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use VisiteurBundle\Entity\Composante;

/**
 * Création des composantes pour le développement
 */
class Fake_Composante implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $composantes = new ArrayCollection();

        $composantes->add(array(
            'nom' => 'ISTIC'
        ));

        $composantes->add(array(
            'nom' => 'ESIR'
        ));

        $composantes->forAll(function($index, array $info) use($manager) {
            $composante = new Composante();
            $composante->setNom($info['nom']);
            
            $manager->persist($composante);
            return true;
        });
        $manager->flush();
    }
}