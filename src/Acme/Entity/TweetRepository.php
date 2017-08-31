<?php

namespace Acme\Entity;

use Doctrine\ORM\EntityRepository;

class TweetRepository extends EntityRepository
{
    /**
     * Get all tweets by user ID
     *
     * @todo Need to DI $app
     * @param int $userId
     * @return array
     */
    public function findByUser($app, $userId)
    {
        $sql = 'SELECT * FROM tweet WHERE user_id = ? ORDER BY id DESC';
        return $app['db']->fetchAll($sql, [(int) $userId]);
    }
}
