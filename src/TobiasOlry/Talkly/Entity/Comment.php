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
     * @Column(type="string")
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

    public function __construct($user, $commentText, Topic $topic)
    {
        $this->createdBy   = $user;
        $this->topic       = $topic;
        $this->createdAt   = new \DateTime();
        $this->commentText = $commentText;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function getCommentText()
    {
        return $this->commentText;
    }

}


