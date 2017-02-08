<?php
namespace VisiteurBundle\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use VisiteurBundle\Entity\AnneeUniversitaire;
use VisiteurBundle\Entity\EtatAnnee;

/**
 * Création d'années univ
 */
class AnneeEtEtat implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {





        $annee = new AnneeUniversitaire();
        $annee->setAnneeScolaire('2016 / 2017');

            
        $etat1 = new EtatAnnee();
        $etat1->setIntitule('Ouverture');
        $etat1->setDescription('OUVERTURE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat1->setMoisDebut('Juillet');
        $etat1->setMoisFin('Juillet');
        $etat1->setSituation(0);
        $etat1->setIdAnnee( $annee);


        $etat2 = new EtatAnnee();
        $etat2->setIntitule('Importation des UE');
        $etat2->setDescription('IMPORTATION DES UE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat2->setMoisDebut('Juillet');
        $etat2->setMoisFin('Aout');
        $etat2->setSituation(1);
        $etat2->setIdAnnee( $annee );


        $etat3 = new EtatAnnee();
        $etat3->setIntitule('Campagne des voeux');
        $etat3->setDescription('CAMPAGNE DES VOEUX -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat3->setMoisDebut('Aout');
        $etat3->setMoisFin('Septembre');
        $etat3->setSituation(2);
        $etat3->setIdAnnee( $annee );



        $etat4 = new EtatAnnee();
        $etat4->setIntitule('Mise en place des services');
        $etat4->setDescription('MISE EN PLACE DES SERVICES -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat4->setMoisDebut('Septembre');
        $etat4->setMoisFin('Novembre');
        $etat4->setSituation(2);
        $etat4->setIdAnnee( $annee );


        $etat5 = new EtatAnnee();
        $etat5->setIntitule('Réalisation et comptabilité des services');
        $etat5->setDescription('REALISATION ET COMPTABILITÉ DES SERVICES -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat5->setMoisDebut('Novembre');
        $etat5->setMoisFin('Juin');
        $etat5->setSituation(2);
        $etat5->setIdAnnee( $annee );


        $etat6 = new EtatAnnee();
        $etat6->setIntitule('Clôture');
        $etat6->setDescription('CLOTURE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat6->setMoisDebut('Juin');
        $etat6->setMoisFin('Juillet');
        $etat6->setSituation(2);
        $etat6->setIdAnnee( $annee );

        $manager->persist($annee);
        $manager->persist($annee);

        $manager->persist($etat1);
        $manager->persist($etat2);
        $manager->persist($etat3);
        $manager->persist($etat4);
        $manager->persist($etat5);
        $manager->persist($etat6);

        $manager->flush();


    }
}