<?php
/*
 *
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Topic", inversedBy="comments")
     */
    private $topic;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
     */
    private $createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * true, if comment was written after
     * the lecture was already held
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $feedback;

    /**
     * @ORM\Column(type="text")
     */
    private $commentText;

    /**
     *
     * @param User $user
     * @param Topic $topic
     * @param string $commentText
     */
    public function __construct(User $user, Topic $topic, $commentText)
    {
        $this->createdBy   = $user;
        $this->topic       = $topic;
        $this->createdAt   = new \DateTime();
        $this->commentText = $commentText;
        $this->feedback    = false;
    }

    /**
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /*
     * @return string
     */
    public function getCommentText()
    {
        return $this->commentText;
    }

    public function getTopic()
    {
        return $this->topic;
    }

    public function isFeedback()
    {
        return $this->feedback;
    }

    public function markAsFeedback()
    {
        $this->feedback = true;
    }
}
