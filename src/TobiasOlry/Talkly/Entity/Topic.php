<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */
namespace TobiasOlry\Talkly\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="TobiasOlry\Talkly\Repository\TopicRepository")
 * @Table
 */
class Topic
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="topics")
     */
    private $createdBy;

    /**
     * @Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @Column(type="string", nullable=true)
     */
    private $title;

    /**
     * @Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ManyToMany(targetEntity="User", inversedBy="votes")
     * @JoinTable(name="vote")
     */
    private $votes;

    /**
     * @OneToMany(targetEntity="Comment", mappedBy="topic", cascade="all")
     */
    private $comments;

    /**
     * @ManyToMany(targetEntity="User", inversedBy="speakingTopics")
     * @JoinTable(name="speaker")
     */
    private $speakers;

    /**
     * @Column(type="text", nullable=true)
     */
    private $lectureNote;

    /**
     * @Column(type="date", nullable=true)
     */
    private $lectureDate;

    /**
     *
     * @Column(type="boolean")
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
        return $this->comments->filter(function($comment) {
            return ! $comment->isFeedback();
        });
    }

    /**
     * @return Comment[]
     */
    public function getFeedbackComments()
    {
        return $this->comments->filter(function($comment) {
            return $comment->isFeedback();
        });
    }

    public function getCommentingUsers()
    {
        $users = array();
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
