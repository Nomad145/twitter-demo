<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class UserProvider
 *
 * Provides an instance of App\Entity\User to the Security component.

 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class UserProvider implements UserProviderInterface
{
    /** @var UserRepository */
    protected $userRepo;

    /**
     * @param UserRepository
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $user = $this->userRepo->findByHandle($username);

        if (!$user instanceof User) {
            throw new UsernameNotFoundException();
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $class === User::class;
    }
}
