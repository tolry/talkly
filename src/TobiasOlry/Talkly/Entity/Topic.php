<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */
namespace TobiasOlry\Talkly\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="TobiasOlry\Talkly\Repository\TopicRepository")
 * @ORM\Table()
 */
class Topic
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="topics")
     */
    private $createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="votes")
     * @ORM\JoinTable(name="vote")
     */
    private $votes;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="topic", cascade="all")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="speakingTopics")
     * @ORM\JoinTable(name="speaker")
     */
    private $speakers;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $lectureNote;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $lectureDate;

    /**
     *
     * @ORM\Column(type="boolean")
     */
    private $lectureHeld;

    /**
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();

        $this->createdBy = $user;

        $this->votes    = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->speakers = new ArrayCollection();

        $this->lectureHeld = false;
    }

    /**
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     * @return Vote[]
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * @return Comment[]
     */
    public function getComments()
    {
        return $this->comments->filter(function(Comment $comment) {
            return ! $comment->isFeedback();
        });
    }

    /**
     * @return Comment[]
     */
    public function getFeedbackComments()
    {
        return $this->comments->filter(function(Comment $comment) {
            return $comment->isFeedback();
        });
    }

    public function getCommentingUsers()
    {
        $users = [];
        foreach ($this->comments as $comment) {
            $users[] = $comment->getCreatedBy();
        }

        return array_unique($users);
    }

    /**
     *
     * @return User[]
     */
    public function getSpeakers()
    {
        return $this->speakers;
    }

    /**
     * @param User $user
     * @param string $text
     *
     * @return Comment
     */
    public function comment(User $user, $text)
    {
        $comment = new Comment($user, $this, $text);

        if ($this->isLectureHeld()) {
            $comment->markAsFeedback();
        }

        $this->comments->add($comment);

        return $comment;
    }

    /**
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
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
     * @param \DateTime $date
     */
    public function setLectureDate(\DateTime $date = null)
    {
        $this->lectureDate = $date;
    }

    /**
     *
     * @return \DateTime
     */
    public function getLectureDate()
    {
        return $this->lectureDate;
    }

    /**
     *
     * @param string $note
     */
    public function setLectureNote($note)
    {
        $this->lectureNote = $note;
    }

    /**
     *
     * @return string
     */
    public function getLectureNote()
    {
        return $this->lectureNote;
    }

    /**
     *
     * @param bool $bool
     */
    public function setLectureHeld($bool = true)
    {
        $this->lectureHeld = $bool;
    }

    /**
     *
     * @return bool
     */
    public function isLectureHeld()
    {
        return $this->lectureHeld;
    }
}
