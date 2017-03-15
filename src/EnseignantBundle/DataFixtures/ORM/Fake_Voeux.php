<?php
namespace EnseignantBundle\DataFixtures;

use Doctrine\Common\Collections\ArrayCollection;
use \Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 01/03/2017
 * Time: 15:32
 */
class Fake_Voeux implements FixtureInterface, ContainerAwareInterface {

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $voeux = new ArrayCollection();

        $voeux->add(array(
        ));

        $voeux->forAll(function($index, array $info) use($manager) {
            $voeux = new Voeux();
            $manager->persist($voeux);
            return true;
        });
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
    }
}