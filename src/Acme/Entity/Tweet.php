<?php

namespace Acme\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Acme\Entity\Tweet
 *
 * @ORM\Entity
 * @ORM\Table(name="tweet")
 * @ORM\Entity(repositoryClass="Acme\Entity\TweetRepository")
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
     * @todo Make User relationship
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $user;

    /**
     * @var string $message
     *
     * @ORM\Column(name="message", type="string", length=140, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 140,
     *      minMessage = "Your message must be at least {{ limit }} characters long",
     *      maxMessage = "Your message cannot be longer than {{ limit }} characters"
     * )
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

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Tweet
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
