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



        /////////////////////////////////////////////////////////// JOUR = 01 / 03 / 2017
        $notifs = new Notifications();
        $notifs->setText("Noël Plouzeau a ajouté un type d'enseignement à l'UE ACO.");
        $notifs->setDatetime(\DateTime::createFromFormat("d/m/Y H:i","03/03/2017 15:00") );
        $notifs->setImportance(1);
        $notifs->setEmeteur(0);

        $notifs2 = new Notifications();
        $notifs2->setText("Votre campagne de voeux n'est pas complète : il manque 32 heures à déclarer.");
        $notifs2->setDatetime(\DateTime::createFromFormat("d/m/Y H:i","01/03/2017 16:00") );
        $notifs2->setImportance(1);
        $notifs2->setEmeteur(0);

        $notifs3 = new Notifications();
        $notifs3->setText("Charles Quéguiner vous a désigné responsable de l'étape M1-INFO GL");
        $notifs3->setDatetime(\DateTime::createFromFormat("d/m/Y H:i","02/03/2017 17:00") );
        $notifs3->setImportance(1);
        $notifs3->setEmeteur(0);

        /////////////////////////////////////////////////////////// JOUR = 01 / 02 / 2017
        $notifs4 = new Notifications();
        $notifs4->setText("L'intervenant Jean DUPONT a choisit une mission (Mission Systèmes Réseaux 001). En attente de validation.");
        $notifs4->setDatetime(\DateTime::createFromFormat("d/m/Y H:i","03/03/2017 11:00") );
        $notifs4->setImportance(1);
        $notifs4->setEmeteur(0);

        $notifs5 = new Notifications();
        $notifs5->setText("Votre campagne de voeux n'est pas complète : il manque 32 heures à déclarer.");
        $notifs5->setDatetime(\DateTime::createFromFormat("d/m/Y H:i","01/03/2017 18:00") );
        $notifs5->setImportance(1);
        $notifs5->setEmeteur(0);

        $notifs6 = new Notifications();
        $notifs6->setText("Charles Quéguiner a créé une nouvel UE : MAN-C++");
        $notifs6->setDatetime(\DateTime::createFromFormat("d/m/Y H:i","01/03/2017 20:00") );
        $notifs6->setImportance(1);
        $notifs6->setEmeteur(0);



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


        $manager->persist($notifs);
        $manager->persist($notifs2);
        $manager->persist($notifs3);
        $manager->persist($notifs4);
        $manager->persist($notifs5);
        $manager->persist($notifs6);


        $manager->persist($asso1);
        $manager->persist($asso2);
        $manager->persist($asso3);
        $manager->persist($asso4);
        $manager->persist($asso5);
        $manager->persist($asso6);



        $manager->flush();


    }
}