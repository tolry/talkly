<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */
namespace TobiasOlry\Talkly\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
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

    public function __construct($user)
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();

        $this->createdBy = $user;

        $this->votes = new ArrayCollection();
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

    public function voteCastBy($user)
    {
        foreach ($this->votes as $vote) {
            if ($vote->getVoter() == $user) {
                return true;
            }
        }

        return false;
    }
}


