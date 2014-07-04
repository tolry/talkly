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
     * @var boolean
     *
     * @Column(type="boolean", nullable=true)
     */
    protected $notifyByEmail;

    /**
     * @var boolean
     *
     * @Column(type="boolean", nullable=true)
     */
    protected $notifyInApplication;

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

        $this->notifyByEmail       = false;
        $this->notifyInApplication = false;
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
     * @return Comment[]
     */
    public function getComments()
    {
        return $this->votes;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getNotifyByEmail()
    {
        return $this->notifyByEmail;
    }

    public function setNotifyByEmail($notifyByEmail)
    {
        $this->notifyByEmail = (boolean) $notifyByEmail;
    }

    public function getNotifyInApplication()
    {
        return $this->notifyInApplication;
    }

    public function setNotifyInApplication($notifyInApplication)
    {
        $this->notifyInApplication = (boolean) $notifyInApplication;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        if (! empty($this->name)) {

            return $this->name;
        }

        return $this->username;
    }
}
