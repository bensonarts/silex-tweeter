<?php

namespace Acme\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Tweet
{
    /**
     * @var User $user
     */
    private $user;

    /**
     * @var string $message
     *
     * @Assert\NotBlank()
     * @Assert\Length(array('min' => 5))
     * *Assert\Length(array('max' => 140))
     */
    private $message;

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
}
