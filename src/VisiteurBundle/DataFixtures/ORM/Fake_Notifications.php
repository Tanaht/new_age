<?php
namespace VisiteurBundle\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use VisiteurBundle\Entity\AnneeUniversitaire;
use VisiteurBundle\Entity\EtatAnnee;
use VisiteurBundle\Entity\Notifications;
use VisiteurBundle\Entity\UtilNotif;

/**
 * Création d'années univ
 */
class Fake_Notifications implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {



        /////////////////////////////////////////////////////////////////////////////////////
        $notifs = new Notifications();
        $notifs->setText(" (1) <a href=\"/noel-plouzeau\">Noël Plouzeau</a> a ajouté un type d'enseignement à l'<a href=\"/ue-aco\">UE ACO</a>.");
        $notifs->setDatetime(\DateTime::createFromFormat("d/m/Y H:i","03/03/2017 18:14") );
        $notifs->setImportance(1);
        $notifs->setEmeteur(0);

        $notifs2 = new Notifications();
        $notifs2->setText(" (2) Votre campagne de voeux n'est pas complète : il manque 32 heures à déclarer.");
        $notifs2->setDatetime(\DateTime::createFromFormat("d/m/Y H:i","03/03/2017 16:25") );
        $notifs2->setImportance(1);
        $notifs2->setEmeteur(0);

        $notifs3 = new Notifications();
        $notifs3->setText(" (3) <a href=\"/charles-queguiner\">Charles Quéguiner</a> vous a désigné responsable de l'étape <a href=\"/m1-info-gl\">M1 INFO GL</a>.");
        $notifs3->setDatetime(\DateTime::createFromFormat("d/m/Y H:i","02/03/2017 11:02") );
        $notifs3->setImportance(1);
        $notifs3->setEmeteur(0);

        ////////////////////////////////////////////////////////////////////////////////
        $notifs4 = new Notifications();
        $notifs4->setText(" (4) L'intervenant <a href=\"/jean-dupont\">Jean Dupont</a> a choisit une mission (<a href=\"/mission-sysrsx-001\">Mission Systèmes Réseaux 001</a>). En attente de validation.");
        $notifs4->setDatetime(\DateTime::createFromFormat("d/m/Y H:i","02/03/2017 10:45") );
        $notifs4->setImportance(1);
        $notifs4->setEmeteur(0);

        $notifs5 = new Notifications();
        $notifs5->setText(" (5) Votre campagne de voeux n'est pas complète : il manque 32 heures à déclarer.");
        $notifs5->setDatetime(\DateTime::createFromFormat("d/m/Y H:i","01/03/2017 17:31") );
        $notifs5->setImportance(1);
        $notifs5->setEmeteur(0);

        $notifs6 = new Notifications();
        $notifs6->setText(" (6) <a href=\"/charles-queguiner\">Charles Quéguiner</a> a créé une nouvel UE : <a href=\"/ue-man-c++\">MAN C++</a>");
        $notifs6->setDatetime(\DateTime::createFromFormat("d/m/Y H:i","01/03/2017 14:04") );
        $notifs6->setImportance(1);
        $notifs6->setEmeteur(0);

        ///////////////////////////////////////////////////////////////////////////
        $notifs7 = new Notifications();
        $notifs7->setText(" (7) L'intervenant <a href=\"/jean-dupont\">Jean Dupont</a> a choisit une mission (<a href=\"/mission-sysrsx-001\">Mission Systèmes Réseaux 001</a>). En attente de validation.");
        $notifs7->setDatetime(\DateTime::createFromFormat("d/m/Y H:i","15/02/2017 14:45") );
        $notifs7->setImportance(1);
        $notifs7->setEmeteur(0);

        $notifs8 = new Notifications();
        $notifs8->setText(" (8) Votre campagne de voeux n'est pas complète : il manque 32 heures à déclarer.");
        $notifs8->setDatetime(\DateTime::createFromFormat("d/m/Y H:i","15/02/2017 11:31") );
        $notifs8->setImportance(1);
        $notifs8->setEmeteur(0);

        $notifs9 = new Notifications();
        $notifs9->setText(" (9) <a href=\"/charles-queguiner\">Charles Quéguiner</a> a créé une nouvel UE : <a href=\"/ue-man-c++\">MAN C++</a>");
        $notifs9->setDatetime(\DateTime::createFromFormat("d/m/Y H:i","10/02/2017 17:04") );
        $notifs9->setImportance(1);
        $notifs9->setEmeteur(0);

        $notifs10 = new Notifications();
        $notifs10->setText(" (10) <a href=\"/charles-queguiner\">Charles Quéguiner</a> a créé une nouvel UE : <a href=\"/ue-man-c++\">MAN C++</a>");
        $notifs10->setDatetime(\DateTime::createFromFormat("d/m/Y H:i","10/02/2017 11:04") );
        $notifs10->setImportance(1);
        $notifs10->setEmeteur(0);

        $notifs11 = new Notifications();
        $notifs11->setText(" (11) <a href=\"/charles-queguiner\">Charles Quéguiner</a> a créé une nouvel UE : <a href=\"/ue-man-c++\">MAN C++</a>");
        $notifs11->setDatetime(\DateTime::createFromFormat("d/m/Y H:i","01/02/2017 19:04") );
        $notifs11->setImportance(1);
        $notifs11->setEmeteur(0);

        $notifs12 = new Notifications();
        $notifs12->setText(" (12) <a href=\"/charles-queguiner\">Charles Quéguiner</a> a créé une nouvel UE : <a href=\"/ue-man-c++\">MAN C++</a>");
        $notifs12->setDatetime(\DateTime::createFromFormat("d/m/Y H:i","01/02/2017 15:04") );
        $notifs12->setImportance(1);
        $notifs12->setEmeteur(0);

        $repo_notif = $manager->getRepository("UserBundle:Utilisateur");
        $antoine = $repo_notif->findOneBy(array("username"=>"AntMu"));
        $tanaky = $repo_notif->findOneBy(array("username"=>"Tanaky"));


        $asso1 = new UtilNotif();
        $asso1->setNotif($notifs);
        $asso1->setUtil($antoine);
        $asso1->setLu(false);

        $asso2 = new UtilNotif();
        $asso2->setNotif($notifs2);
        $asso2->setUtil($antoine);
        $asso2->setLu(false);

        $asso3 = new UtilNotif();
        $asso3->setNotif($notifs3);
        $asso3->setUtil($antoine);
        $asso3->setLu(false);

        $asso4 = new UtilNotif();
        $asso4->setNotif($notifs4);
        $asso4->setUtil($antoine);
        $asso4->setLu(false);

        $asso5 = new UtilNotif();
        $asso5->setNotif($notifs5);
        $asso5->setUtil($antoine);
        $asso5->setLu(false);

        $asso6 = new UtilNotif();
        $asso6->setNotif($notifs6);
        $asso6->setUtil($antoine);
        $asso6->setLu(false);

        $asso7 = new UtilNotif();
        $asso7->setNotif($notifs7);
        $asso7->setUtil($antoine);
        $asso7->setLu(false);

        $asso8 = new UtilNotif();
        $asso8->setNotif($notifs8);
        $asso8->setUtil($antoine);
        $asso8->setLu(false);

        $asso9 = new UtilNotif();
        $asso9->setNotif($notifs9);
        $asso9->setUtil($antoine);
        $asso9->setLu(false);

        $asso10 = new UtilNotif();
        $asso10->setNotif($notifs10);
        $asso10->setUtil($antoine);
        $asso10->setLu(false);

        $asso11 = new UtilNotif();
        $asso11->setNotif($notifs11);
        $asso11->setUtil($antoine);
        $asso11->setLu(false);

        $asso12 = new UtilNotif();
        $asso12->setNotif($notifs12);
        $asso12->setUtil($antoine);
        $asso12->setLu(false);


        $manager->persist($notifs);
        $manager->persist($notifs2);
        $manager->persist($notifs3);
        $manager->persist($notifs4);
        $manager->persist($notifs5);
        $manager->persist($notifs6);
        $manager->persist($notifs7);
        $manager->persist($notifs8);
        $manager->persist($notifs9);
        $manager->persist($notifs10);
        $manager->persist($notifs11);
        $manager->persist($notifs12);


        $manager->persist($asso1);
        $manager->persist($asso2);
        $manager->persist($asso3);
        $manager->persist($asso4);
        $manager->persist($asso5);
        $manager->persist($asso6);
        $manager->persist($asso7);
        $manager->persist($asso8);
        $manager->persist($asso9);
        $manager->persist($asso10);
        $manager->persist($asso11);
        $manager->persist($asso12);




        $manager->flush();


    }
}