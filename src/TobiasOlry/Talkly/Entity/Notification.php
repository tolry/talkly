<?php
/*
 *
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Entity;

/**
 * @Table
 * @Entity
 */
class Notification
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="notifications")
     */
    private $user;

    /**
     * @Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Column(type="text")
     */
    private $message;

    /**
     * @Column(type="boolean")
     */
    private $done;

    /**
     * @param User $user
     * @param string $message
     */
    public function __construct(User $user, $message)
    {
        $this->user    = $user;
        $this->message = $message;

        $this->done      = false;
        $this->createdAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /*
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    public function isDone()
    {
        return $this->done;
    }

    public function markAsDone()
    {
        $this->done = true;
    }
}
