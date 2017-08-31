<?php

namespace Acme\Repository;

use Doctrine\ORM\EntityRepository;

class TweetRepository extends EntityRepository
{
    /**
     * Get all tweets by user ID
     *
     * @param int $userId
     * @return array
     */
    public function findByUser($userId)
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        return $qb->getResult();
    }
}
