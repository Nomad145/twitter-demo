<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\User;

/**
 * Class UserRepository
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class UserRepository extends EntityRepository
{
    /**
     * Finds a User by their username.
     *
     * @param string $handle
     * @return User|null
     */
    public function findByHandle(string $handle) :? User
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.handle = :handle')
            ->setParameter(':handle', $handle)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Checks if a User contains a unique email and username.
     *
     * @param User
     * @return bool
     */
    public function isUnique(User $user) : bool
    {
        return !(bool) $this->createQueryBuilder('u')
            ->select('count(u)')
            ->where('u.handle = :handle')
            ->orWhere('u.email = :email')
            ->setParameter(':handle', $user->getHandle())
            ->setParameter(':email', $user->getEmail())
            ->getQuery()
            ->getSingleScalarResult();
    }
}
