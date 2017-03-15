<?php
namespace VisiteurBundle\DataFixtures;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use VisiteurBundle\Entity\Composante;
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
            'name' => 'M1-INFO',
            'composante' => 'ISTIC'
        ));

        $etapes->add(array(
            'name' => 'M2-INFO',
            'composante' => 'ISTIC'
        ));

        $etapes->forAll(function($index, array $info) use($manager) {
            $etape = new etape();
            $etape->setComposante($manager->getRepository(Composante::class)->findOneBy(['nom' => $info['composante']]));
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