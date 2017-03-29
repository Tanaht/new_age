<?php
namespace VisiteurBundle\DataFixtures;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use VisiteurBundle\Entity\UE;
use VisiteurBundle\Entity\Etape;

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
            'name' => 'ACO',
            'description' => 'lorem ipsum blablablabla',
            'etape' => 'M1-INFO'
        ));

        $ues->add(array(
            'name' => 'ACF',
            'description' => 'lorem ipsum blablablabla',
            'etape' => 'M1-INFO'
        ));

        $ues->add(array(
            'name' => 'COMP',
            'description' => 'lorem ipsum blablablabla',
            'etape' => 'M1-INFO'
        ));

        $ues->add(array(
            'name' => 'MFDS',
            'description' => 'lorem ipsum blablablabla',
            'etape' => 'M1-INFO'
        ));

        $ues->add(array(
            'name' => 'AOC',
            'description' => 'lorem ipsum blablablabla',
            'etape' => 'M2-INFO'
        ));

        $ues->add(array(
            'name' => 'RSIP',
            'description' => 'lorem ipsum blablablabla',
            'etape' => 'M2-INFO'
        ));

        $ues->add(array(
            'name' => 'ANGLAIS',
            'description' => 'English is good !',
            'etape' => 'M2-INFO'
        ));

        $ues->add(array(
            'name' => 'ECO',
            'description' => "Un module d'économie au top !" ,
            'etape' => 'M2-INFO'
        ));

        $ues->forAll(function($index, array $info) use($manager) {
            $ue = new ue();
            $ue->setName($info['name']);
            $ue->setDescription($info['description']);

            //On affecte l'UE dans une étape
            $em = $manager->getRepository("VisiteurBundle:Etape");
            $etape = $em->findOneBy(array("name"=> $info['etape']));  
            $etape->addUe($ue);

            //On rajoute le responsable
            $em = $manager->getRepository("UserBundle:Utilisateur");
            $utilisateur = $em->findOneBy(array("username"=>"AntMu"));
            $ue->setResponsable($utilisateur);
            $manager->persist($ue);
            return true;
        });
        $manager->flush();
    }
}