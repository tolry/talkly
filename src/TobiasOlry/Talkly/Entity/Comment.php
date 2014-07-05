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
class Comment
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Topic", inversedBy="comments")
     */
    private $topic;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="comments")
     */
    private $createdBy;

    /**
     * @Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Column(type="text")
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
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /*
     *
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
}
