<?php
namespace VisiteurBundle\DataFixtures;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use VisiteurBundle\Entity\Mission;

/**
 * Création des missions pour le développement
 */
class Fake_Missions implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $Mission = new Mission();
        $em = $manager->getRepository("VisiteurUser:Utilisateur");
        $postulant = $em->findOneBy(array("username"=>"Morganol"));

        $Missions->setCandidat();
        $manager->flush();
    }
}
