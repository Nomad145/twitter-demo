<?php

namespace App\Repository;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use App\Entity\User;

/**
 * Class TweetRepository
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class TweetRepository extends EntityRepository
{
    /**
     * Find all Tweets by a given user, ordering by the date posted.
     *
     * @param User
     * @return Tweet[]
     */
    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.user = :user')
            ->setParameter('user', $user)
            ->orderBy('t.datePosted', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
