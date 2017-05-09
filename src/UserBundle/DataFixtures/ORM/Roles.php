<?php
/**
 * Created by PhpStorm.
 * User: tanna
 * Date: 08/03/2017
 * Time: 15:17
 */

namespace UserBundle\DataFixtures\ORM;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\Role;

class Roles implements FixtureInterface {

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $roles = new ArrayCollection([
            [
                'nom' => "Responsable Comptable",
                'slug' =>  "ROLE_RESP_COMPTABLE"
            ],
            [
                'nom' => "Responsable d'étape",
                'slug' =>  "ROLE_RESP_ETAPE"
            ],
            [
                'nom' => "Response service",
                'slug' =>  "ROLE_RESP_SERVICE"
            ],
            [
                'nom' => "Responsable unité d'enseignement",
                'slug' =>  "ROLE_RESP_UE"
            ],
            [
                'nom' => "Visiteur",
                'slug' =>  "ROLE_VISITEUR"
            ],
            [
                'nom' => "Intervenant",
                'slug' =>  "ROLE_INTERVENANT"
            ],
            [
                'nom' => "Enseignant",
                'slug' =>  "ROLE_ENSEIGNANT"
            ],
        ]);

        $roles->forAll(function($index, array $infos) use($manager) {
            $role = new Role();
            $role->setNom($infos['nom']);
            $role->setSlug($infos['slug']);

            $manager->persist($role);
            return true;
        });

        $manager->flush();
    }
}