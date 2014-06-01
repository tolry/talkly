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
     * @Column(type="string", nullable=true)
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
     * @OneToMany(targetEntity="Vote", mappedBy="topic", cascade="all")
     */
    private $votes;

    /**
     * @OneToMany(targetEntity="Comment", mappedBy="topic", cascade="all")
     */
    private $comments;

    /**
     * @Column(type="text", nullable=true)
     */
    private $lectureNote;

    /**
     * @Column(type="string", nullable=true)
     */
    private $lectureUser;

    /**
     * @Column(type="date", nullable=true)
     */
    private $lectureDate;

    public function __construct($user)
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();

        $this->createdBy = $user;

        $this->votes    = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function castVote($user)
    {
        if ($this->voteCastBy($user)) {
            return false;
        }

        $vote = new Vote($this, $user);
        $this->votes->add($vote);
    }

    public function comment($user, $text)
    {
        $this->comments->add(new Comment($user, $text, $this));
    }

    public function getVote($user)
    {
        if (! $this->voteCastBy($user)) {
            return;
        }

        foreach ($this->votes as $vote) {
            if ($vote->getVoter() == $user) {
                return $vote;
            }
        }

        return;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getVotes()
    {
        return $this->votes;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function voteCastBy($user)
    {
        foreach ($this->votes as $vote) {
            if ($vote->getVoter() == $user) {
                return true;
            }
        }

        return false;
    }

    public function getLectureDate()
    {
        return $this->lectureDate;
    }

    public function getLectureNote()
    {
        return $this->lectureNote;
    }

    public function getLectureUser()
    {
        return $this->lectureUser;
    }

    public function setLectureDetails(\DateTime $date, $user, $note)
    {
        $this->lectureDate = $date;
        $this->lectureUser = $user;
        $this->lectureNote = $note;
    }

    public function isArchived()
    {
        return $this->lectureDate <> null;
    }
}


