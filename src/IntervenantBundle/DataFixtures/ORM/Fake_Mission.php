<?php
namespace IntervenantBundle\DataFixtures;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use IntervenantBundle\Entity\Mission;

/**
 * Création des missions pour le développement
 */
class Fake_Mission implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $mission1 = new ArrayCollection();
        $mission1->add(array("nom"=>"Ma première mission","statut"=>'FERMEE'));


        $mission1->forAll(function($index,array $info) use($manager){
            $mission1 = new Mission();
            $mission1->setName($info['nom']);
            $mission1->setStatut($info['statut']);

            $repo_composante = $manager->getRepository("VisiteurBundle:Composante");
            $composante = $repo_composante->findOneBy(array("nom"=>"ISTIC"));
            $mission1->setComposante($composante);

            $em = $manager->getRepository("UserBundle:Utilisateur");
            $user = $em->findOneBy(array("username"=>"antmu"));

            $mission1->addCandidat($user);
            $mission1->setIntervenant($user);

            $em = $manager->getRepository("UserBundle:Utilisateur");
            $user2 = $em->findOneBy(array("username"=>"tanaky"));

            $mission1->addCandidat($user2);

            $repo_cours = $manager->getRepository("VisiteurBundle:Cours");
            $cours = $repo_cours->findOneBy(array("nom"=>"ACO"));

            $repo_voeux = $manager->getRepository("VisiteurBundle:Voeux");
            $voeux = $repo_voeux->findOneBy(array("cours"=>$cours));

            $cours2 = $repo_cours->findOneBy(array("nom"=>"MFDS"));
            $voeux2 = $repo_voeux->findOneBy(array("cours"=>$cours2));

            $mission1->addVoeux($voeux);
            $mission1->addVoeux($voeux2);

            $manager->persist($mission1);
            return true;
        });

        $mission2 = new ArrayCollection();
        $mission2->add(array("nom"=>"Une mission libre","statut"=>'LIBRE'));


        $mission2->forAll(function($index,array $info) use($manager) {
            $mission2 = new Mission();
            $mission2->setName($info['nom']);
            $mission2->setStatut($info['statut']);

            $repo_composante = $manager->getRepository("VisiteurBundle:Composante");
            $composante = $repo_composante->findOneBy(array("nom"=>"ISTIC"));
            $mission2->setComposante($composante);

            $em = $manager->getRepository("UserBundle:Utilisateur");
            $user = $em->findOneBy(array("username"=>"antmu"));
            $mission2->addCandidat($user);

            $repo_cours = $manager->getRepository("VisiteurBundle:Cours");
            $cours = $repo_cours->findOneBy(array("nom"=>"ACO"));

            $repo_voeux = $manager->getRepository("VisiteurBundle:Voeux");
            $voeux = $repo_voeux->findOneBy(array("cours"=>$cours));

            $mission2->addVoeux($voeux);

            $manager->persist($mission2);
            return true;
        });


        $manager->flush();
    }
}
