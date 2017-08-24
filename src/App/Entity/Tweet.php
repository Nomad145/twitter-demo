<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Tweet
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 *
 * @ORM\Table(name="tweet")
 * @ORM\Entity(repositoryClass="App\Repository\TweetRepository")
 */
class Tweet
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(name="message", type="string", length=140, nullable=false)
     */
    private $message;

    /**
     * @ORM\Column(name="date_posted", type="datetime")
     */
    private $datePosted;

    /**
     * Constructs a new instance with the current \DateTime.
     */
    public function __construct()
    {
        $this->datePosted = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser() :? User
    {
        return $this->user;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage() : ? string
    {
        return $this->message;
    }

    /**
     * @return \DateTime
     */
    public function getDatePosted() : \DateTime
    {
        return $this->datePosted;
    }

    /**
     * @param \DateTime
     * @return $this
     */
    public function setDatePosted(\DateTime $datePosted)
    {
        $this->datePosted = $datePosted;

        return $this;
    }
}
