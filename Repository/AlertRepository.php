<?php

namespace Jamesi\NotificationBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

use Jamesi\NotificationBundle\Model\NotifiableInterface;

class AlertRepository extends EntityRepository
{
    /**
     * @param NotifiableInterface $user
     * @return ArrayCollection
     */
    public function getUnseen(NotifiableInterface $user)
    {
        return $this->getEntityManager()
            // TODO - TPN hardcoded, read from config using manager
            ->createQuery('SELECT a FROM TPNNotificationBundle:Alert a ' .
                'WHERE a.deleted = false AND a.seen = false AND a.user = :user ' .
                'ORDER BY a.timeFired DESC')
            ->setParameter('user', $user)
            ->getResult();
    }

    /**
     * @param NotifiableInterface $user
     * @return int
     */
    public function getUnseenCount(NotifiableInterface $user)
    {
        return count($this->getUnseen($user));
    }

    /**
     * @param NotifiableInterface $user
     * @param int $limit
     * @return ArrayCollection
     */
    public function getAllForUser(NotifiableInterface $user, $limit = 0)
    {
        $query = $this->getEntityManager()
            // TODO - TPN hardcoded, read from config using manager
            ->createQuery('SELECT a FROM TPNNotificationBundle:Alert a ' .
                'WHERE a.deleted = false AND a.user = :user ' .
                'ORDER BY a.timeFired DESC')
            ->setParameter('user', $user);
        if ($limit) {
            $query->setMaxResults($limit);
        }
        return $query->getResult();
    }

    /**
     * @param NotifiableInterface $user
     * @param int $minimum
     * @return ArrayCollection
     */
    public function getRecentAlerts(NotifiableInterface $user, $minimum = 5)
    {
        $alerts = $this->getUnseen($user);
        if (count($alerts) < $minimum) {
            $alerts = $this->getAllForUser($user, $minimum);
        }

        return $alerts;
    }

    /**
     * Mark all the user's alerts as seen (because they viewed the alerts page)
     *
     * @param NotifiableInterface $user
     */
    public function setAlertsSeen(NotifiableInterface $user)
    {
        // TODO - TPN hardcoded, read from config using manager
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->update('TPNNotificationBundle:Alert', 'a')
            ->set('a.seen', 'true')
            ->where($qb->expr()->eq('a.user', ':user'))
            ->setParameter('user', $user)
        ;
        
        $qb->getQuery()->execute();
    }
}