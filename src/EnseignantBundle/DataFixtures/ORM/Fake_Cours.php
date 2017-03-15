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
class Fake_Cours implements FixtureInterface, ContainerAwareInterface {

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $cours = new ArrayCollection();

        $cours->add(array(
            'nbh' => 20
        ));

        $cours->forAll(function($index, array $info) use($manager) {
            $cours = new Cours();
            $cours->setNbh($info['nbh']);

            $manager->persist($cours);
            return true;
        });
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
    }
}