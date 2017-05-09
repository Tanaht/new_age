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
        $etat1->setDescription("L'ouverture de l'année permet d'initialiser correctement le processus de fonction de NewAGE pour débuter un nouveau cycle.");
        $etat1->setMoisDebut('Juillet');
        $etat1->setMoisFin('Juillet');
        $etat1->setEncours(false);
        $etat1->setOrdre(1);
        $etat1->setIdAnnee( $annee);


        $etat2 = new EtatAnnee();
        $etat2->setIntitule('Importation des UE');
        $etat2->setDescription("L'importation des UE permet au responsable des services d'importer les unités d'enseignements, les étapes, les types d'enseignements de la maquette");
        $etat2->setMoisDebut('Juillet');
        $etat2->setMoisFin('Aout');
        $etat2->setEncours(true);
        $etat2->setOrdre(2);
        $etat2->setIdAnnee( $annee );


        $etat3 = new EtatAnnee();
        $etat3->setIntitule('Campagne des voeux');
        $etat3->setDescription('Lors de la campagne des voeux les personnes abilitées à faire des voeux peuvent commencer leurs saisies de voeux.');
        $etat3->setMoisDebut('Aout');
        $etat3->setMoisFin('Septembre');
        $etat3->setEncours(false);
        $etat3->setOrdre(3);
        $etat3->setIdAnnee( $annee );



        $etat4 = new EtatAnnee();
        $etat4->setIntitule('Mise en place des services');
        $etat4->setDescription('La mise en place des services permet au responsables des services de valider les voeux émis lors de la phase précédente.');
        $etat4->setMoisDebut('Septembre');
        $etat4->setMoisFin('Novembre');
        $etat4->setEncours(false);
        $etat4->setOrdre(4);
        $etat4->setIdAnnee( $annee );


        $etat5 = new EtatAnnee();
        $etat5->setIntitule('Réalisation et comptabilité  des services');
        $etat5->setDescription('Gestion de la comptabilité, de la balance entre UFR.');
        $etat5->setMoisDebut('Novembre');
        $etat5->setMoisFin('Juin');
        $etat5->setEncours(false);
        $etat5->setIdAnnee( $annee );
        $etat5->setOrdre(5);


        $etat6 = new EtatAnnee();
        $etat6->setIntitule('Clôture');
        $etat6->setDescription("Clotûre de l'année universitaire.");
        $etat6->setMoisDebut('Juin');
        $etat6->setMoisFin('Juillet');
        $etat6->setEncours(false);
        $etat6->setIdAnnee( $annee );
        $etat6->setOrdre(6);


        //Année 2015/2016
        $annee1 = new AnneeUniversitaire();
        $annee1->setAnneeScolaire('2015 / 2016');


        $etat7 = new EtatAnnee();
        $etat7->setIntitule('Ouverture');
        $etat7->setDescription('2015 / 2016 OUVERTURE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat7->setMoisDebut('Juillet');
        $etat7->setMoisFin('Juillet');
        $etat7->setEncours(false);
        $etat7->setIdAnnee( $annee1 );
        $etat7->setOrdre(1);


        $etat8 = new EtatAnnee();
        $etat8->setIntitule('Importation des UE');
        $etat8->setDescription('2015 / 2016 IMPORTATION DES UE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat8->setMoisDebut('Juillet');
        $etat8->setMoisFin('Aout');
        $etat8->setEncours(false);
        $etat8->setIdAnnee( $annee1 );
        $etat8->setOrdre(2);



        $etat10 = new EtatAnnee();
        $etat10->setIntitule('Mise en place des services');
        $etat10->setDescription('MISE EN PLACE DES SERVICES -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat10->setMoisDebut('Septembre');
        $etat10->setMoisFin('Novembre');
        $etat10->setEncours(false);
        $etat10->setIdAnnee( $annee1 );
        $etat10->setOrdre(3);

        $etat11 = new EtatAnnee();
        $etat11->setIntitule('Réalisation et compta.  des services');
        $etat11->setDescription('REALISATION ET COMPTABILITÉ DES SERVICES -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat11->setMoisDebut('Novembre');
        $etat11->setMoisFin('Juin');
        $etat11->setEncours(true);
        $etat11->setIdAnnee( $annee1 );
        $etat11->setOrdre(4);

        $etat12 = new EtatAnnee();
        $etat12->setIntitule('Clôture');
        $etat12->setDescription('CLOTURE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat12->setMoisDebut('Juin');
        $etat12->setMoisFin('Juillet');
        $etat12->setEncours(false);
        $etat12->setIdAnnee( $annee1 );
        $etat12->setOrdre(5);

        //Année 2014/2015
        $annee2 = new AnneeUniversitaire();
        $annee2->setAnneeScolaire('2014 / 2015');


        $etat13 = new EtatAnnee();
        $etat13->setIntitule('Ouverture');
        $etat13->setDescription('OUVERTURE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat13->setMoisDebut('Juillet');
        $etat13->setMoisFin('Juillet');
        $etat13->setEncours(false);
        $etat13->setIdAnnee( $annee2 );
        $etat13->setOrdre(1);

        $etat14 = new EtatAnnee();
        $etat14->setIntitule('Importation des UE');
        $etat14->setDescription('IMPORTATION DES UE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat14->setMoisDebut('Juillet');
        $etat14->setMoisFin('Aout');
        $etat14->setEncours(false);
        $etat14->setIdAnnee( $annee2 );
        $etat14->setOrdre(2);

        $etat141 = new EtatAnnee();
        $etat141->setIntitule('Complément enseignement');
        $etat141->setDescription('COMPLEMENT ENSEIGNEMENT-> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat141->setMoisDebut('Janvier');
        $etat141->setMoisFin('Décembre');
        $etat141->setEncours(false);
        $etat141->setIdAnnee( $annee2 );
        $etat141->setOrdre(3);

        $etat15 = new EtatAnnee();
        $etat15->setIntitule('Campagne des voeux');
        $etat15->setDescription('CAMPAGNE DES VOEUX -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat15->setMoisDebut('Aout');
        $etat15->setMoisFin('Septembre');
        $etat15->setEncours(false);
        $etat15->setIdAnnee( $annee2 );
        $etat15->setOrdre(4);


        $etat16 = new EtatAnnee();
        $etat16->setIntitule('Mise en place des services');
        $etat16->setDescription('MISE EN PLACE DES SERVICES -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat16->setMoisDebut('Septembre');
        $etat16->setMoisFin('Novembre');
        $etat16->setEncours(false);
        $etat16->setIdAnnee( $annee2 );
        $etat16->setOrdre(5);

        $etat17 = new EtatAnnee();
        $etat17->setIntitule('Réalisation et compta. des services');
        $etat17->setDescription('REALISATION ET COMPTABILITÉ DES SERVICES -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat17->setMoisDebut('Novembre');
        $etat17->setMoisFin('Juin');
        $etat17->setEncours(false);
        $etat17->setIdAnnee( $annee2 );
        $etat17->setOrdre(6);

        $etat18 = new EtatAnnee();
        $etat18->setIntitule('Clôture');
        $etat18->setDescription('CLOTURE -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat18->setMoisDebut('Juin');
        $etat18->setMoisFin('Juillet');
        $etat18->setEncours(true);
        $etat18->setIdAnnee( $annee2 );
        $etat18->setOrdre(7);

        //Année 2013/2014
        $annee3 = new AnneeUniversitaire();
        $annee3->setAnneeScolaire('2013 / 2014');

        $etat23 = new EtatAnnee();
        $etat23->setIntitule('Campagne de voeux');
        $etat23->setDescription('Campagne de voeux -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat23->setMoisDebut('Juin');
        $etat23->setMoisFin('Juillet');
        $etat23->setEncours(true);
        $etat23->setIdAnnee( $annee3 );
        $etat23->setOrdre(2);

        $etat24 = new EtatAnnee();
        $etat24->setIntitule('Cloture');
        $etat24->setDescription('Cloture -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat24->setMoisDebut('Juin');
        $etat24->setMoisFin('Juillet');
        $etat24->setEncours(false);
        $etat24->setIdAnnee( $annee3 );
        $etat24->setOrdre(3);

        $etat19 = new EtatAnnee();
        $etat19->setIntitule('Ouverture');
        $etat19->setDescription('Ouverture -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat19->setMoisDebut('Juillet');
        $etat19->setMoisFin('Juillet');
        $etat19->setIdAnnee( $annee3 );
        $etat19->setEncours(false);
        $etat19->setOrdre(1);


        //Année 2013/2014
        $annee4 = new AnneeUniversitaire();
        $annee4->setAnneeScolaire('2012 / 2013');

        $etat25 = new EtatAnnee();
        $etat25->setIntitule('UNE SEULE ETAPE');
        $etat25->setDescription('Une seule étape ... très curieux ... ');
        $etat25->setMoisDebut('Janvier');
        $etat25->setMoisFin('Décembre');
        $etat25->setIdAnnee( $annee4 );
        $etat25->setEncours(true);
        $etat25->setOrdre(1);



        $annee5 = new AnneeUniversitaire();
        $annee5->setAnneeScolaire('2011 / 2012');


        $etat26 = new EtatAnnee();
        $etat26->setIntitule('Campagne de voeux');
        $etat26->setDescription('Campagne de voeux -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat26->setMoisDebut('Juin');
        $etat26->setMoisFin('Juillet');
        $etat26->setEncours(false);
        $etat26->setIdAnnee( $annee5 );
        $etat26->setOrdre(2);

        $etat27 = new EtatAnnee();
        $etat27->setIntitule('Cloture');
        $etat27->setDescription('Cloture -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat27->setMoisDebut('Juin');
        $etat27->setMoisFin('Juillet');
        $etat27->setEncours(false);
        $etat27->setIdAnnee( $annee5 );
        $etat27->setOrdre(3);

        $etat28 = new EtatAnnee();
        $etat28->setIntitule('Ouverture');
        $etat28->setDescription('Ouverture -> Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
        $etat28->setMoisDebut('Juillet');
        $etat28->setMoisFin('Juillet');
        $etat28->setEncours(false);
        $etat28->setIdAnnee( $annee5 );
        $etat28->setOrdre(1);

        $manager->persist($annee);
        $manager->persist($annee1);
        $manager->persist($annee2);
        $manager->persist($annee3);
        $manager->persist($annee4);
        $manager->persist($annee5);

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
        $manager->persist($etat23);
        $manager->persist($etat24);
        $manager->persist($etat25);

        $manager->persist($etat26);
        $manager->persist($etat27);
        $manager->persist($etat28);





        $manager->flush();


    }
}