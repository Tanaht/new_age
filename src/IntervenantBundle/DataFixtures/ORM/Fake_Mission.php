<?php
namespace IntervenantBundle\DataFixtures;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use IntervenantBundle\Entity\Mission;
use JMS\Serializer\Exception\LogicException;
use VisiteurBundle\Entity\Composante;
use VisiteurBundle\Entity\Cours;
use VisiteurBundle\Entity\UE;
use VisiteurBundle\Entity\Voeux;

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
        $mission1 = $this->createMission1($manager);
        $manager->persist($mission1);

        $mission2 = $this->createMission2($manager);
        $manager->persist($mission2);

        $manager->flush();

    }

    private function createMission1(ObjectManager $manager) {
        $mission = new Mission();

        $mission->setName('M1-Info-GL Cours Magistraux');
        $composante = $manager->getRepository(Composante::class)->findOneBy(['nom' => 'ISTIC']);
        $mission->setComposante($composante);

        $aco = $manager->getRepository(UE::class)->findOneBy(['name' => 'ACO']);
        $comp = $manager->getRepository(UE::class)->findOneBy(['name' => 'COMP']);
        $se = $manager->getRepository(UE::class)->findOneBy(['name' => 'SE']);

        /**
         * @var ArrayCollection $coursAcos
         */
        $coursAcos = $aco->getCours()->filter(function(Cours $cours) {
            return $cours->getType() == "CM";
        });

        /**
         * @var ArrayCollection $coursComps
         */
        $coursComps = $comp->getCours()->filter(function(Cours $cours) {
            return $cours->getType() == "CM";
        });

        /**
         * @var ArrayCollection $coursSes
         */
        $coursSes = $se->getCours()->filter(function(Cours $cours) {
            return $cours->getType() == "CM";
        });


        $coursAco = $coursAcos[$coursAcos->getKeys()[0]];

        $coursComp = $coursComps[$coursComps->getKeys()[0]];

        $coursSe = $coursSes[$coursSes->getKeys()[0]];

        $voeuxACO = new Voeux();
        $voeuxACO->setCours($coursAco);
        $voeuxACO->setNbHeures(20);

        $voeuxComp = new Voeux();
        $voeuxComp->setCours($coursComp);
        $voeuxComp->setNbHeures(5);

        $voeuxSe = new Voeux();
        $voeuxSe->setCours($coursSe);
        $voeuxSe->setNbHeures(10);

        $mission->addVoeux($voeuxACO);
        $mission->addVoeux($voeuxComp);
        $mission->addVoeux($voeuxSe);
        return $mission;
    }


    private function createMission2(ObjectManager $manager) {
        $mission = new Mission();

        $mission->setName('M1-Info-GL Travaux Dirigés');
        $composante = $manager->getRepository(Composante::class)->findOneBy(['nom' => 'ISTIC']);
        $mission->setComposante($composante);

        $aco = $manager->getRepository(UE::class)->findOneBy(['name' => 'ACO']);
        $comp = $manager->getRepository(UE::class)->findOneBy(['name' => 'COMP']);
        $se = $manager->getRepository(UE::class)->findOneBy(['name' => 'SE']);

        $coursAcos = $aco->getCours()->filter(function(Cours $cours)
        {
            return $cours->getType() == "TD";
        });

        $coursComps = $comp->getCours()->filter(function(Cours $cours) {
            return $cours->getType() == "TD";
        });

        $coursSes = $se->getCours()->filter(function(Cours $cours) {
            return $cours->getType() == "TD";
        });

        $coursAco = $coursAcos[$coursAcos->getKeys()[0]];

        $coursComp = $coursComps[$coursComps->getKeys()[0]];

        $coursSe = $coursSes[$coursSes->getKeys()[0]];

        $voeuxACO = new Voeux();
        $voeuxACO->setCours($coursAco);
        $voeuxACO->setNbHeures(20);

        $voeuxComp = new Voeux();
        $voeuxComp->setCours($coursComp);
        $voeuxComp->setNbHeures(15);

        $voeuxSe = new Voeux();
        $voeuxSe->setCours($coursSe);
        $voeuxSe->setNbHeures(15);

        $mission->addVoeux($voeuxACO);
        $mission->addVoeux($voeuxComp);
        $mission->addVoeux($voeuxSe);
        return $mission;
    }
}
