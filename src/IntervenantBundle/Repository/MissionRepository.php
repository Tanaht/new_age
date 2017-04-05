<?php

namespace IntervenantBundle\Repository;
use Doctrine\ORM\EntityRepository;
use IntervenantBundle\Entity\Mission;
use Symfony\Component\DependencyInjection\Container;
use UserBundle\Entity\Utilisateur;

/**
 * MissionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MissionRepository extends EntityRepository
{
	/**
     * @var Container;
     */
    private $container;


    /**
     * @param $status
     * @return integer
     */
    public function countByStatus($status) {

        $qb = $this->createQueryBuilder('countMissions');

        $qb->select($qb->expr()->count('countMissions.id'));

        switch($status) {
            case 'all':
                break;
            case 'disponible':
                $qb->andWhere($qb->expr()->eq('missions.statut', Mission::STATUT_LIBRE));
                break;
            case 'non-disponible':
                $qb->andWhere($qb->expr()->eq('missions.statut', Mission::STATUT_FERMEE));
                break;
            case 'archive':
                $qb->andWhere($qb->expr()->eq('missions.statut', Mission::STATUT_ARCHIVEE));
                break;
        }
        return $qb->getQuery()->getResult()[0][1];
    }

    /**
     * @param $name
     * @param $itemsByPage
     * @param $currentPage
     * @param all|non-disponible|archive|disponible $status
     * @return array
     */
    public function getMissionsFilteredByName($name, $itemsByPage, $currentPage, $status) {
        $qb = $this->createQueryBuilder('missions');

        $qb
            ->where($qb->expr()->like('missions.name', ':name'))
            ->setFirstResult(($currentPage - 1) * $itemsByPage)
            ->setMaxResults($currentPage * $itemsByPage)
            ->setParameter(':name', '%'.$name.'%')
        ;

        switch($status) {
            case 'all':
                break;
            case 'disponible':
                $qb->andWhere($qb->expr()->eq('missions.statut', Mission::STATUT_LIBRE));
                break;
            case 'non-disponible':
                $qb->andWhere($qb->expr()->eq('missions.statut', Mission::STATUT_FERMEE));
                break;
            case 'archive':
                $qb->andWhere($qb->expr()->eq('missions.statut', Mission::STATUT_ARCHIVEE));
                break;
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function getMissionsFromUser(Utilisateur $utilisateur){

        $composante = $utilisateur->getComposante();

        $queryBuilder = $this->createQueryBuilder('mission')->select('mission.id, mission.name')->where("mission.composante = :composante")->setParameter("composante", $composante);

        return $queryBuilder->getQuery()->getResult();
    }
}
