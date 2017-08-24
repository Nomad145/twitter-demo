<?php

namespace Security;

use PHPUnit\Framework\TestCase;
use App\Entity\User;
use App\Security\UserProvider;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class UserProviderTest
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class UserProviderTest extends TestCase
{
    protected $repo;

    public function setUp()
    {
        $this->repo = $this->createMock(UserRepository::class);
    }

    /**
     * @group unittest
     */
    public function testUserFound()
    {
        $user = (new User())
            ->setHandle('user');

        $this->repo
            ->method('findByHandle')
            ->willReturn($user);

        $subject = new UserProvider($this->repo);

        $this->assertSame($user, $subject->loadUserByUsername('user'));
        $this->assertSame($user, $subject->refreshUser($user));
    }

    /**
     * @group unittest
     */
    public function testUserNotFound()
    {
        $this->repo
            ->method('findByHandle')
            ->willReturn(null);

        $subject = new UserProvider($this->repo);

        $this->expectException(UsernameNotFoundException::class);

        $subject->loadUserByUsername('user');
    }
}
