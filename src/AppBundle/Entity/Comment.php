<?php
/*
 *
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as JSON;

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
     *
     * @JSON\Groups({"topic_show"})
     */
    private $createdBy;

    /**
     * @ORM\Column(type="datetime")
     *
     * @JSON\Groups({"topic_show"})
     */
    private $createdAt;

    /**
     * true, if comment was written after
     * the lecture was already held
     *
     * @ORM\Column(type="boolean")
     */
    private $feedback;

    /**
     * @ORM\Column(type="text")
     *
     * @JSON\Groups({"topic_show"})
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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * @return string
     */
    public function getCommentText()
    {
        return $this->commentText;
    }

    /**
     * @return Topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @return bool
     */
    public function isFeedback()
    {
        return $this->feedback;
    }

    /**
     *
     */
    public function markAsFeedback()
    {
        $this->feedback = true;
    }
}
