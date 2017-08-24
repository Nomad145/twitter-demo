<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="handle", type="string", length=15, nullable=false)
     */
    protected $handle;

    /**
     * @ORM\Column(name="password", type="string", nullable=false)
     */
    protected $password;

    /**
     * @ORM\Column(name="first_name", type="string", nullable=false)
     */
    protected $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", nullable=false)
     */
    protected $lastName;

    /**
     * @ORM\Column(name="email", type="string", nullable=false)
     */
    protected $email;

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->getHandle();
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return sprintf('%s %s', $this->getFirstName(), $this->getLastName());
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        $this->password = null;
    }

    /**
     * There only needs to be one role for this application.
     *
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * BCrypt handles its own salt, but the Interface requires this method.
     *
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @param string
     * @return $this
     */
    public function setHandle(string $handle)
    {
        $this->handle = $handle;

        return $this;
    }

    /**
     * If the user is registered, the encoded password is returned.
     *
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string
     * @return $this
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string
     * @return $this
     */
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string
     * @return $this
     */
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string
     * @return $this
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }
}
