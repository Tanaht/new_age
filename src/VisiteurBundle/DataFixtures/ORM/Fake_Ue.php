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
            'description' => "L'unité d'enseignement ACO présente les techniques et les outils de développement utilisés actuellement par les informaticiens. Il est composé de quatre parties : l'approfondissement de la connaissance du langage UML, la présentation de la démarche d'analyse et de conception, l'étude des bonnes pratiques de construction d'architectures logicielles par objets et la problématique de la qualité et du test. Les travaux pratiques utilisent la notation UML.

À l'issue de ce cours, un étudiant doit être capable de concevoir une application à objets simple à partir d'un cahier des charges : en mettant en oeuvre une démarche d'analyse et de conception par objets, en utilisant le langage UML comme langage pivot, en construisant une architecture par objets robuste et préparée aux futures évolutions de l'application, en garantissant le fonctionnement de son application grâce à la mise en place d'un jeu de tests unitaire, d'intégration et fonctionnelle.",
            'etape' => 'M1-INFO'
        ));

        $ues->add(array(
            'name' => 'ACF',
            'description' => "L’UE ACF vise à initier les étudiants à l’utilisation de méthodes formelles pour la spécification et le développement de logiciels sûrs. L’accent est mis sur la compréhension des formules logiques et sur leur utilisation pour la spécification de propriétés de programmes. Les programmes considérés seront définis dans un style fonctionnel. L'outil de développement formel utilisé est Isabelle/HOL et le langage d'exportation et d'intégration choisi est Scala.",
            'etape' => 'M1-INFO'
        ));

        $ues->add(array(
            'name' => 'COMP',
            'description' => "Cette unité d'enseignement présente les composants fondamentaux d'un compilateur et les principales techniques de compilation utilisées dans ces composants. Une attention particulière est portée sur la face arrière (back-end) du compilateur. Les travaux dirigés et travaux pratiques ont pour but la construction d'un compilateur pour un langage impératif.

À l'issue de ce module, l'étudiant saura conduire un projet de programmation dirigée par la syntaxe et dans le cas de traduction dirigée par la syntaxe, il saura distinguer ce qui relève des langages source et cible et du langage de programmation de la traduction. Dans le cas de traduction de langage de programmation (commande, script, etc.), l'étudiant saura prévoir le comportement dynamique du programme cible et conduire des analyses élémentaires de ce comportement.",
            'etape' => 'M1-INFO'
        ));

        $ues->add(array(
            'name' => 'MFDS',
            'description' => 'L’UE MFDS présente une méthode formelle utilisée pour construire de façon raisonnée des logiciels qui se comportent conformément à leur spécification. Le style de méthode formelle choisi est la vérification déductive. Il est introduit en utilisant l’outil KeY dédié à la spécification en JML de programmes Java. La vérification formelle de programmes Java annotés par des spécifications JML repose sur une traduction dans une logique introduite dans cette UE. Cette UE explique également comment utiliser conjointement le test et la vérification formelle. Elle présente quelques techniques de test, en complément de celles introduites dans l’UE ACO.',
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

            $em = $manager->getRepository("UserBundle:Utilisateur");
            $utilisateur2 = $em->findOneBy(array("username"=>"Tanaky"));

            if($index%2){
                $ue->setResponsable($utilisateur);
            }
            else{
                $ue->setResponsable($utilisateur2);
            }
            $manager->persist($ue);
            return true;
        });
        $manager->flush();
    }
}