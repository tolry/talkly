<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Entity;

/**
 * @Entity
 */
class Vote
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="votes")
     */
    private $voter;

    /**
     * @Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ManyToOne(targetEntity="Topic", inversedBy="votes")
     */
    private $topic;

    /**
     *
     * @param Topic $topic
     * @param User $voter
     */
    public function __construct(Topic $topic, User $voter)
    {
        $this->topic     = $topic;
        $this->createdAt = new \DateTime();
        $this->voter     = $voter;
    }

    /**
     *
     * @return User
     */
    public function getVoter()
    {
        return $this->voter;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->voter;
    }
}
