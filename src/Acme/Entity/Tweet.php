<?php

namespace Acme\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Acme\Entity\Tweet
 *
 * @ORM\Entity
 * @ORM\Table(name="tweet")
 * @ORM\Entity(repositoryClass="Acme\Repository\TweetRepository")
 */
class Tweet
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="int", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var User $user
     *
     * @ORM\Column(name="message", type="string", length=140, nullable=false)
     */
    private $user;

    /**
     * @var string $message
     *
     * @ORM\Column(name="message", type="string", length=140, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(array('min' => 5))
     * *Assert\Length(array('max' => 140))
     */
    private $message;

    /**
     * @var DateTime $createdAt Created at timestamp
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Tweet
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getId()
    {
        return $this->id;
    }
}
