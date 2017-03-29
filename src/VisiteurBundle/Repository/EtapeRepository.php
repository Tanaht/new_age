<?php

namespace VisiteurBundle\Repository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\Container;
use UserBundle\Entity\Utilisateur;

/**
 * EtapeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EtapeRepository extends EntityRepository
{
    /**
     * @var Container;
     */
    private $container;

    /**
     * @return array
     */
    public function getEtapesFromUser(Utilisateur $utilisateur){

        $composante = $utilisateur->getComposante();

        $queryBuilder = $this->createQueryBuilder('etape')->select('etape.id, etape.name')->where("etape.composante = :composante")->setParameter("composante", $composante);

        return $queryBuilder->getQuery()->getResult();


    }
}