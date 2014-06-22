<?php

namespace TobiasOlry\Talkly\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use TobiasOlry\Talkly\Security\UserInterface;

/**
 *
 * @author David Badura <d.a.badura@gmail.com>
 *
 * @Entity
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
     * @OneToMany(targetEntity="Topic", mappedBy="lectureUser")
     */
    protected $lectures;

    /**
     *
     * @OneToMany(targetEntity="Vote", mappedBy="voter")
     */
    protected $votes;

    /**
     *
     * @param string $username
     */
    public function __construct($username)
    {
        $this->username = $username;

        $this->comments = new ArrayCollection();
        $this->topics   = new ArrayCollection();
        $this->lectures = new ArrayCollection();
        $this->votes    = new ArrayCollection();
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
     * @return string
     */
    public function __toString()
    {
        return $this->username;
    }
}
