<?php
namespace VisiteurBundle\DataFixtures;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use VisiteurBundle\Entity\Etape;

/**
 * Création des etapes pour le développement
 */
class Fake_etape implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $etapes = new ArrayCollection();

        $etapes->add(array(
            'name' => 'M1-INFO'
        ));

        $etapes->add(array(
            'name' => 'M2-INFO'
        ));

        $etapes->forAll(function($index, array $info) use($manager) {
            $etape = new etape();
            $etape->setName($info['name']);
            $em = $manager->getRepository("VisiteurBundle:UE");
            $ues = $em->findAll();
            foreach($ues as $ue) {
                $etape->addUe($ue);
            }
            $em = $manager->getRepository("UserBundle:Utilisateur");
            $utilisateur = $em->findOneBy(array("username"=>"antmu"));
            $etape->setResponsable($utilisateur);
            $manager->persist($etape);
            return true;
        });
        $manager->flush();
    }
}