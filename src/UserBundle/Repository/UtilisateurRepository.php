<?php

namespace UserBundle\Repository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use VisiteurBundle\Entity\Email;

/**
 * UtilisateurRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UtilisateurRepository extends \Doctrine\ORM\EntityRepository
{
    public function getUsers() {
        $queryBuilder = $this->createQueryBuilder('u')->select('concat(u.nom, \' \',u.prenom) as display_name, u.id');
        return $queryBuilder->getQuery()->getResult();


    }

    public function testQueryBuilderPossibilities() {
        $qb = $this->createQueryBuilder('u')->select('u', 'e', 'n')->distinct(true)
            ->innerJoin('u.email_list', 'e')
            ->innerJoin('u.num_list', 'n')
        ;


        $query = $this->getEntityManager()->createQuery(
            "SELECT composante_25, etapes_28, responsable_32, ues_36, responsable_40, cours_44 FROM VisiteurBundle\Entity\Composante composante_25 INNER JOIN composante_25.etapes etapes_28 INNER JOIN etapes_28.responsable responsable_32 INNER JOIN etapes_28
.ues ues_36 INNER JOIN ues_36.responsable responsable_40 INNER JOIN ues_36.cours cours_44"
        );

        dump($query->getArrayResult());

        return $query->getScalarResult();
    }
}
