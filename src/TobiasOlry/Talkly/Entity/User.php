<?php

namespace TobiasOlry\Talkly\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use TobiasOlry\Talkly\Security\UserInterface;

/**
 *
 * @author David Badura <d.a.badura@gmail.com>
 * @author Tobias Olry <tobias.olry@gmail.com>
 *
 * @Entity
 * @Table(name="UserProfil")
 */
class User implements UserInterface
{

    /**
     * @var int
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @var string
     *
     * @Column(type="string")
     */
    protected $username;

    /**
     * @var string
     *
     * @Column(type="string")
     */
    protected $name;

    /**
     * @var string
     *
     * @Column(type="string", nullable=true)
     */
    protected $email;

    /**
     *
     * @OneToMany(targetEntity="Comment", mappedBy="createdBy")
     */
    protected $comments;

    /**
     *
     * @OneToMany(targetEntity="Topic", mappedBy="createdBy")
     */
    protected $topics;

    /**
     *
     * @ManyToMany(targetEntity="Topic", mappedBy="speakers")
     */
    protected $speakingTopics;

    /**
     *
     * @OneToMany(targetEntity="Topic", mappedBy="lectureUser")
     */
    protected $lectures;

    /**
     *
     * @ManyToMany(targetEntity="Topic", mappedBy="votes")
     */
    protected $votes;

    /**
     *
     * @param string $username
     */
    public function __construct($username)
    {
        $this->username = $username;

        $this->comments       = new ArrayCollection();
        $this->topics         = new ArrayCollection();
        $this->lectures       = new ArrayCollection();
        $this->votes          = new ArrayCollection();
        $this->speakingTopics = new ArrayCollection();
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
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     *
     * @return Topic[]
     */
    public function getSpeakingTopics()
    {
        return $this->speakingTopics;
    }

    /**
     *
     * @return Topic[]
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->username;
    }
}
