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

        //Année 2016/2017
        $annee = new AnneeUniversitaire();
        $annee->setAnneeScolaire('2016 / 2017');

            
        $etat1 = new EtatAnnee();
        $etat1->setIntitule('Ouverture');
        $etat1->setDescription(' 2016 / 2017 OUVERTURE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat1->setMoisDebut('Juillet');
        $etat1->setMoisFin('Juillet');
        $etat1->setSituation(0);
        $etat1->setIdAnnee( $annee);


        $etat2 = new EtatAnnee();
        $etat2->setIntitule('Importation des UE');
        $etat2->setDescription(' 2016 / 2017 IMPORTATION DES UE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat2->setMoisDebut('Juillet');
        $etat2->setMoisFin('Aout');
        $etat2->setSituation(1);
        $etat2->setIdAnnee( $annee );


        $etat3 = new EtatAnnee();
        $etat3->setIntitule('Campagne des voeux');
        $etat3->setDescription('2016 / 2017 CAMPAGNE DES VOEUX -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat3->setMoisDebut('Aout');
        $etat3->setMoisFin('Septembre');
        $etat3->setSituation(2);
        $etat3->setIdAnnee( $annee );



        $etat4 = new EtatAnnee();
        $etat4->setIntitule('Mise en place des services');
        $etat4->setDescription('2016 / 2017 MISE EN PLACE DES SERVICES -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat4->setMoisDebut('Septembre');
        $etat4->setMoisFin('Novembre');
        $etat4->setSituation(2);
        $etat4->setIdAnnee( $annee );


        $etat5 = new EtatAnnee();
        $etat5->setIntitule('Réalisation et compta.  des services');
        $etat5->setDescription('REALISATION ET COMPTABILITÉ DES SERVICES -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat5->setMoisDebut('Novembre');
        $etat5->setMoisFin('Juin');
        $etat5->setSituation(2);
        $etat5->setIdAnnee( $annee );


        $etat6 = new EtatAnnee();
        $etat6->setIntitule('Clôture');
        $etat6->setDescription('2016 / 2017 CLOTURE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat6->setMoisDebut('Juin');
        $etat6->setMoisFin('Juillet');
        $etat6->setSituation(2);
        $etat6->setIdAnnee( $annee );

        //Année 2015/2016
        $annee1 = new AnneeUniversitaire();
        $annee1->setAnneeScolaire('2015 / 2016');


        $etat7 = new EtatAnnee();
        $etat7->setIntitule('Ouverture');
        $etat7->setDescription('2015 / 2016 OUVERTURE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat7->setMoisDebut('Juillet');
        $etat7->setMoisFin('Juillet');
        $etat7->setSituation(0);
        $etat7->setIdAnnee( $annee1 );


        $etat8 = new EtatAnnee();
        $etat8->setIntitule('Importation des UE');
        $etat8->setDescription('2015 / 2016 IMPORTATION DES UE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat8->setMoisDebut('Juillet');
        $etat8->setMoisFin('Aout');
        $etat8->setSituation(0);
        $etat8->setIdAnnee( $annee1 );


        $etat10 = new EtatAnnee();
        $etat10->setIntitule('Mise en place des services');
        $etat10->setDescription('MISE EN PLACE DES SERVICES -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat10->setMoisDebut('Septembre');
        $etat10->setMoisFin('Novembre');
        $etat10->setSituation(0);
        $etat10->setIdAnnee( $annee1 );


        $etat11 = new EtatAnnee();
        $etat11->setIntitule('Réalisation et compta.  des services');
        $etat11->setDescription('REALISATION ET COMPTABILITÉ DES SERVICES -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat11->setMoisDebut('Novembre');
        $etat11->setMoisFin('Juin');
        $etat11->setSituation(1);
        $etat11->setIdAnnee( $annee1 );


        $etat12 = new EtatAnnee();
        $etat12->setIntitule('Clôture');
        $etat12->setDescription('CLOTURE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat12->setMoisDebut('Juin');
        $etat12->setMoisFin('Juillet');
        $etat12->setSituation(2);
        $etat12->setIdAnnee( $annee1 );

        //Année 2014/2015
        $annee2 = new AnneeUniversitaire();
        $annee2->setAnneeScolaire('2014 / 2015');


        $etat13 = new EtatAnnee();
        $etat13->setIntitule('Ouverture');
        $etat13->setDescription('OUVERTURE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat13->setMoisDebut('Juillet');
        $etat13->setMoisFin('Juillet');
        $etat13->setSituation(0);
        $etat13->setIdAnnee( $annee2 );


        $etat14 = new EtatAnnee();
        $etat14->setIntitule('Importation des UE');
        $etat14->setDescription('IMPORTATION DES UE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat14->setMoisDebut('Juillet');
        $etat14->setMoisFin('Aout');
        $etat14->setSituation(0);
        $etat14->setIdAnnee( $annee2 );

        $etat141 = new EtatAnnee();
        $etat141->setIntitule('Complément enseignement');
        $etat141->setDescription('COMPLEMENT ENSEIGNEMENT-> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat141->setMoisDebut('Janvier');
        $etat141->setMoisFin('Décembre');
        $etat141->setSituation(0);
        $etat141->setIdAnnee( $annee2 );


        $etat15 = new EtatAnnee();
        $etat15->setIntitule('Campagne des voeux');
        $etat15->setDescription('CAMPAGNE DES VOEUX -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat15->setMoisDebut('Aout');
        $etat15->setMoisFin('Septembre');
        $etat15->setSituation(0);
        $etat15->setIdAnnee( $annee2 );



        $etat16 = new EtatAnnee();
        $etat16->setIntitule('Mise en place des services');
        $etat16->setDescription('MISE EN PLACE DES SERVICES -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat16->setMoisDebut('Septembre');
        $etat16->setMoisFin('Novembre');
        $etat16->setSituation(0);
        $etat16->setIdAnnee( $annee2 );


        $etat17 = new EtatAnnee();
        $etat17->setIntitule('Réalisation et compta. des services');
        $etat17->setDescription('REALISATION ET COMPTABILITÉ DES SERVICES -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat17->setMoisDebut('Novembre');
        $etat17->setMoisFin('Juin');
        $etat17->setSituation(0);
        $etat17->setIdAnnee( $annee2 );


        $etat18 = new EtatAnnee();
        $etat18->setIntitule('Clôture');
        $etat18->setDescription('CLOTURE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat18->setMoisDebut('Juin');
        $etat18->setMoisFin('Juillet');
        $etat18->setSituation(1);
        $etat18->setIdAnnee( $annee2 );

        //Année 2013/2014
        $annee3 = new AnneeUniversitaire();
        $annee3->setAnneeScolaire('2013 / 2014');


        $etat19 = new EtatAnnee();
        $etat19->setIntitule('Ouverture');
        $etat19->setDescription('OUVERTURE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat19->setMoisDebut('Juillet');
        $etat19->setMoisFin('Juillet');
        $etat19->setSituation(0);
        $etat19->setIdAnnee( $annee3 );


        $etat20 = new EtatAnnee();
        $etat20->setIntitule('Importation des UE');
        $etat20->setDescription('IMPORTATION DES UE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat20->setMoisDebut('Juillet');
        $etat20->setMoisFin('Aout');
        $etat20->setSituation(0);
        $etat20->setIdAnnee( $annee3 );


        $etat21 = new EtatAnnee();
        $etat21->setIntitule('Campagne des voeux');
        $etat21->setDescription('CAMPAGNE DES VOEUX -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat21->setMoisDebut('Aout');
        $etat21->setMoisFin('Septembre');
        $etat21->setSituation(0);
        $etat21->setIdAnnee( $annee3 );



        $etat22 = new EtatAnnee();
        $etat22->setIntitule('Mise en place des services');
        $etat22->setDescription('MISE EN PLACE DES SERVICES -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat22->setMoisDebut('Septembre');
        $etat22->setMoisFin('Novembre');
        $etat22->setSituation(0);
        $etat22->setIdAnnee( $annee3 );


        $etat23 = new EtatAnnee();
        $etat23->setIntitule('Réalisation et compta.  des services');
        $etat23->setDescription('REALISATION ET COMPTABILITÉ DES SERVICES -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat23->setMoisDebut('Novembre');
        $etat23->setMoisFin('Juin');
        $etat23->setSituation(0);
        $etat23->setIdAnnee( $annee3 );


        $etat24 = new EtatAnnee();
        $etat24->setIntitule('Clôture');
        $etat24->setDescription('CLOTURE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat24->setMoisDebut('Juin');
        $etat24->setMoisFin('Juillet');
        $etat24->setSituation(0);
        $etat24->setIdAnnee( $annee3 );

        //Année 2013/2014
        $annee4 = new AnneeUniversitaire();
        $annee4->setAnneeScolaire('2012 / 2013');

        $etat25 = new EtatAnnee();
        $etat25->setIntitule('UNE SEULE ETAPE ?');
        $etat25->setDescription('Une seule étape ... très curieux ... ');
        $etat25->setMoisDebut('Janvier');
        $etat25->setMoisFin('Décembre');
        $etat25->setSituation(1);
        $etat25->setIdAnnee( $annee4 );


        $manager->persist($annee);
        $manager->persist($annee1);
        $manager->persist($annee2);
        $manager->persist($annee3);
        $manager->persist($annee4);


        $manager->persist($etat1);
        $manager->persist($etat2);
        $manager->persist($etat3);
        $manager->persist($etat4);
        $manager->persist($etat5);
        $manager->persist($etat6);
        $manager->persist($etat7);
        $manager->persist($etat8);
        $manager->persist($etat10);
        $manager->persist($etat11);
        $manager->persist($etat12);
        $manager->persist($etat13);
        $manager->persist($etat14);
        $manager->persist($etat141);
        $manager->persist($etat15);
        $manager->persist($etat16);
        $manager->persist($etat17);
        $manager->persist($etat18);
        $manager->persist($etat19);
        $manager->persist($etat20);
        $manager->persist($etat21);
        $manager->persist($etat22);
        $manager->persist($etat23);
        $manager->persist($etat24);
        $manager->persist($etat25);



        $manager->flush();


    }
}