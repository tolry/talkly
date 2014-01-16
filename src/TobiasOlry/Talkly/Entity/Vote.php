<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Entity;

/**
 * @Entity
 * @Table
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
     * @Column(type="string", nullable=true)
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

    public function __construct(Topic $topic, $voter)
    {
        $this->topic     = $topic;
        $this->createdAt = new \DateTime();
        $this->voter     = $voter;
    }

    public function getVoter()
    {
        return $this->voter;
    }

    public function __toString()
    {
        return $this->voter;
    }

}

