<?php
/*
 *
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\TalklyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as JSON;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Notification
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     *
     * @JSON\Groups({"notification"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="notifications")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     *
     * @JSON\Groups({"notification"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text")
     *
     * @JSON\Groups({"notification"})
     */
    private $message;

    /**
     * @ORM\Column(type="boolean")
     *
     * @JSON\Groups({"notification"})
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
