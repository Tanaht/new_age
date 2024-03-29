<?php

namespace VisiteurBundle\Repository;
use DateTime;
use DateInterval;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Console\Command\RunDqlCommand;
use UserBundle\Entity\Utilisateur;

/**
 * NotificationsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NotificationRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Retourne les notifications d'un utilisateur en fonction d'une période.
     * @param Utilisateur $recepteur
     * @param DateTime $date Date de début
     * @param int $nbMonths nombre de mois après la date de début
     * @return array
     */
    public function getNotifications(Utilisateur $recepteur, DateTime $date, $nbMonths = 1) {
        $builder = $this->createQueryBuilder('notification');

        $builder
            ->where($builder->expr()->eq('notification.recepteur', ':recepteur'))
            ->andWhere($builder->expr()->between('notification.datetime', ':date_debut', ':date_fin'))
            ->orderBy('notification.datetime', 'desc')
        ;

        $query = $builder->getQuery();

        $dateFin = DateTime::createFromFormat(DateTime::ISO8601, $date->format(DateTime::ISO8601));
        $dateFin->modify('+' . $nbMonths . ' month');

        $query->setParameters([
            'recepteur' => $recepteur,
            'date_debut' => $date,
            'date_fin' => $dateFin
        ]);

        return $query->getResult(Query::HYDRATE_OBJECT);
    }
}
