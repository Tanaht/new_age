<?php
namespace VisiteurBundle\DataFixtures;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use UserBundle\Entity\Utilisateur;
use VisiteurBundle\Entity\AnneeUniversitaire;
use VisiteurBundle\Entity\EtatAnnee;
use VisiteurBundle\Entity\Notification;
use VisiteurBundle\Entity\UtilNotif;

/**
 * Création d'années univ
 */
class Fake_Notifications implements FixtureInterface
{
    /**
     * @var PropertyAccessor
     */
    private $accessor;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {


        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->v1($manager);
        //$this->v2($manager); DEAD CODE



    }

    private function v2(ObjectManager $manager) {

        $amullier = $manager->getRepository(Utilisateur::class)->findOneBy(['username' => 'AntMu']);
        $charpentier = $manager->getRepository(Utilisateur::class)->findOneBy(['username' => 'Tanaky']);

        $infos = new ArrayCollection([
            new ParameterBag([
                'text' => 'som text',
                'datetime' => DateTime::createFromFormat("d/m/Y H:i", date("d/m/Y H:i")),
                'recepteur' => $charpentier,
                'emetteur' => $amullier,
                'importance' => Notification::IMPORTANT
            ]),
            new ParameterBag([
                'text' => 'som text 2',
                'datetime' => DateTime::createFromFormat("d/m/Y H:i", date("d/m/Y H:i")),
                'recepteur' => $charpentier,
                'emetteur' => $amullier,
                'importance' => Notification::IMPORTANT
            ]),
            new ParameterBag([
                'text' => 'som text 3',
                'datetime' => DateTime::createFromFormat("d/m/Y H:i", date("d/m/Y H:i")),
                'recepteur' => $charpentier,
                'emetteur' => $amullier,
                'importance' => Notification::IMPORTANT
            ]),
            new ParameterBag([
                'text' => 'som text 4',
                'datetime' => DateTime::createFromFormat("d/m/Y H:i", date("d/m/Y H:i")),
                'recepteur' => $charpentier,
                'emetteur' => $amullier,
                'importance' => Notification::IMPORTANT
            ]),
            new ParameterBag([
                'text' => 'som text 5',
                'datetime' => DateTime::createFromFormat("d/m/Y H:i", date("d/m/Y H:i")),
                'recepteur' => $charpentier,
                'emetteur' => $amullier,
                'importance' => Notification::IMPORTANT
            ]),
            new ParameterBag([
                'text' => 'som text 6',
                'datetime' => DateTime::createFromFormat("d/m/Y H:i", "25/02/2017 16:25"),
                'recepteur' => $charpentier,
                'emetteur' => $amullier,
                'importance' => Notification::IMPORTANT
            ]),
            new ParameterBag([
                'text' => 'som text 7',
                'datetime' => DateTime::createFromFormat("d/m/Y H:i", "15/03/2017 16:25"),
                'recepteur' => $charpentier,
                'emetteur' => $amullier,
                'importance' => Notification::IMPORTANT
            ]),
            new ParameterBag([
                'text' => 'som text 8',
                'datetime' => DateTime::createFromFormat("d/m/Y H:i", "03/02/2017 16:25"),
                'recepteur' => $amullier,
                'emetteur' => $charpentier,
                'importance' => Notification::IMPORTANT
            ]),
            new ParameterBag([
                'text' => 'som text 9',
                'datetime' => DateTime::createFromFormat("d/m/Y H:i", "03/02/2017 16:25"),
                'recepteur' => $amullier,
                'emetteur' => $charpentier,
                'importance' => Notification::IMPORTANT
            ]),
            new ParameterBag([
                'text' => 'som text 10',
                'datetime' => DateTime::createFromFormat("d/m/Y H:i", "03/03/2017 16:25"),
                'recepteur' => $amullier,
                'emetteur' => $charpentier,
                'importance' => Notification::IMPORTANT
            ]),
        ]);

        $infos->forAll(function($key, ParameterBag $datas) use($manager) {

            $notification = new Notification();

            foreach ($datas as $key => $value) {
                if($this->accessor->isWritable($notification, $key))
                    $this->accessor->setValue($notification, $key, $value);
                else {
                    echo "Unable to write " . $key . " in " . get_class($notification) . "\n";
                }
            }

            $manager->persist($notification);
            return true;
        });

        $manager->flush();
    }


    private function v1(ObjectManager $manager) {
        $repo_notif = $manager->getRepository("UserBundle:Utilisateur");
        $antoine = $repo_notif->findOneBy(array("username"=>"AntMu"));
        $tanaky = $repo_notif->findOneBy(array("username"=>"Tanaky"));
        
        /////////////////////////////////////////////////////////////////////////////////////
        $notifs = new Notification();
        $notifs->setText(" (1) <a href=\"/noel-plouzeau\">Noël Plouzeau</a> a ajouté un type d'enseignement à l'<a href=\"/ue-aco\">UE ACO</a>.");
        $notifs->setDatetime(DateTime::createFromFormat("d/m/Y H:i","03/03/2017 18:14") );
        $notifs->setImportance(Notification::IMPORTANT);
        $notifs->setEmetteur($tanaky);

        $notifs2 = new Notification();
        $notifs2->setText(" (2) Votre campagne de voeux n'est pas complète : il manque 32 heures à déclarer.");
        $notifs2->setDatetime(DateTime::createFromFormat("d/m/Y H:i","03/03/2017 16:25") );
        $notifs2->setImportance(Notification::IMPORTANT);
        $notifs2->setEmetteur($tanaky);

        $notifs3 = new Notification();
        $notifs3->setText(" (3) <a href=\"/charles-queguiner\">Charles Quéguiner</a> vous a désigné responsable de l'étape <a href=\"/m1-info-gl\">M1 INFO GL</a>.");
        $notifs3->setDatetime(DateTime::createFromFormat("d/m/Y H:i","02/03/2017 11:02") );
        $notifs3->setImportance(Notification::IMPORTANT);
        $notifs3->setEmetteur($tanaky);

        ////////////////////////////////////////////////////////////////////////////////
        $notifs4 = new Notification();
        $notifs4->setText(" (4) L'intervenant <a href=\"/jean-dupont\">Jean Dupont</a> a choisit une mission (<a href=\"/mission-sysrsx-001\">Mission Systèmes Réseaux 001</a>). En attente de validation.");
        $notifs4->setDatetime(DateTime::createFromFormat("d/m/Y H:i","02/03/2017 10:45") );
        $notifs4->setImportance(Notification::IMPORTANT);
        $notifs4->setEmetteur($tanaky);

        $notifs5 = new Notification();
        $notifs5->setText(" (5) Votre campagne de voeux n'est pas complète : il manque 32 heures à déclarer.");
        $notifs5->setDatetime(DateTime::createFromFormat("d/m/Y H:i","01/03/2017 17:31") );

        $notifs5->setImportance(Notification::IMPORTANT);
        $notifs5->setEmetteur($tanaky);

        $notifs6 = new Notification();
        $notifs6->setText(" (6) <a href=\"/charles-queguiner\">Charles Quéguiner</a> a créé une nouvel UE : <a href=\"/ue-man-c++\">MAN C++</a>");
        $notifs6->setDatetime(DateTime::createFromFormat("d/m/Y H:i","01/03/2017 14:04") );
        $notifs6->setImportance(Notification::IMPORTANT);
        $notifs6->setEmetteur($tanaky);

        ///////////////////////////////////////////////////////////////////////////
        $notifs7 = new Notification();
        $notifs7->setText(" (7) L'intervenant <a href=\"/jean-dupont\">Jean Dupont</a> a choisit une mission (<a href=\"/mission-sysrsx-001\">Mission Systèmes Réseaux 001</a>). En attente de validation.");
        $notifs7->setDatetime(DateTime::createFromFormat("d/m/Y H:i","15/02/2017 14:45") );
        $notifs7->setImportance(Notification::IMPORTANT);
        $notifs7->setEmetteur($tanaky);

        $notifs8 = new Notification();
        $notifs8->setText(" (8) Votre campagne de voeux n'est pas complète : il manque 32 heures à déclarer.");
        $notifs8->setDatetime(DateTime::createFromFormat("d/m/Y H:i","15/02/2017 11:31") );
        $notifs8->setImportance(Notification::IMPORTANT);
        $notifs8->setEmetteur($tanaky);

        $notifs9 = new Notification();
        $notifs9->setText(" (9) <a href=\"/charles-queguiner\">Charles Quéguiner</a> a créé une nouvel UE : <a href=\"/ue-man-c++\">MAN C++</a>");
        $notifs9->setDatetime(DateTime::createFromFormat("d/m/Y H:i","10/02/2017 17:04") );
        $notifs9->setImportance(Notification::IMPORTANT);
        $notifs9->setEmetteur($tanaky);

        $notifs10 = new Notification();
        $notifs10->setText(" (10) <a href=\"/charles-queguiner\">Charles Quéguiner</a> a créé une nouvel UE : <a href=\"/ue-man-c++\">MAN C++</a>");
        $notifs10->setDatetime(DateTime::createFromFormat("d/m/Y H:i","10/02/2017 11:04") );
        $notifs10->setImportance(Notification::IMPORTANT);
        $notifs10->setEmetteur($tanaky);

        $notifs11 = new Notification();
        $notifs11->setText(" (11) <a href=\"/charles-queguiner\">Charles Quéguiner</a> a créé une nouvel UE : <a href=\"/ue-man-c++\">MAN C++</a>");
        $notifs11->setDatetime(DateTime::createFromFormat("d/m/Y H:i","01/02/2017 19:04") );
        $notifs11->setImportance(Notification::IMPORTANT);
        $notifs11->setEmetteur($tanaky);

        $notifs12 = new Notification();
        $notifs12->setText(" (12) <a href=\"/charles-queguiner\">Charles Quéguiner</a> a créé une nouvel UE : <a href=\"/ue-man-c++\">MAN C++</a>");
        $notifs12->setDatetime(DateTime::createFromFormat("d/m/Y H:i","01/02/2017 15:04") );
        $notifs12->setImportance(Notification::IMPORTANT);
        $notifs12->setEmetteur($tanaky);

        


        $asso1 = new UtilNotif();
        $asso1->setNotif($notifs);
        $asso1->setUtilisateur($antoine);
        $asso1->setLu(true);

        $asso2 = new UtilNotif();
        $asso2->setNotif($notifs2);
        $asso2->setUtilisateur($antoine);
        $asso2->setLu(true);

        $asso3 = new UtilNotif();
        $asso3->setNotif($notifs3);
        $asso3->setUtilisateur($antoine);
        $asso3->setLu(true);

        $asso4 = new UtilNotif();
        $asso4->setNotif($notifs4);
        $asso4->setUtilisateur($antoine);
        $asso4->setLu(false);

        $asso5 = new UtilNotif();
        $asso5->setNotif($notifs5);
        $asso5->setUtilisateur($antoine);
        $asso5->setLu(false);

        $asso6 = new UtilNotif();
        $asso6->setNotif($notifs6);
        $asso6->setUtilisateur($antoine);
        $asso6->setLu(false);

        $asso7 = new UtilNotif();
        $asso7->setNotif($notifs7);
        $asso7->setUtilisateur($antoine);
        $asso7->setLu(false);

        $asso8 = new UtilNotif();
        $asso8->setNotif($notifs8);
        $asso8->setUtilisateur($antoine);
        $asso8->setLu(false);

        $asso9 = new UtilNotif();
        $asso9->setNotif($notifs9);
        $asso9->setUtilisateur($antoine);
        $asso9->setLu(false);

        $asso10 = new UtilNotif();
        $asso10->setNotif($notifs10);
        $asso10->setUtilisateur($antoine);
        $asso10->setLu(false);

        $asso11 = new UtilNotif();
        $asso11->setNotif($notifs11);
        $asso11->setUtilisateur($antoine);
        $asso11->setLu(false);

        $asso12 = new UtilNotif();
        $asso12->setNotif($notifs12);
        $asso12->setUtilisateur($antoine);
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