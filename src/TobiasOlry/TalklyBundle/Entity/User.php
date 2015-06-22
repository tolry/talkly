<?php

namespace TobiasOlry\TalklyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 *
 * @author David Badura <d.a.badura@gmail.com>
 * @author Tobias Olry <tobias.olry@gmail.com>
 *
 * @ORM\Entity()
 * @ORM\Table(name="UserProfil")
 */
class User implements UserInterface
{

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $notifyByEmail;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $notifyInApplication;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;

    /**
     * @var ArrayCollection|Notification[]
     *
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="user", cascade="all")
     */
    protected $notifications;

    /**
     * @var ArrayCollection|Comment[]
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="createdBy")
     */
    protected $comments;

    /**
     * @var ArrayCollection|Topic[]
     *
     * @ORM\OneToMany(targetEntity="Topic", mappedBy="createdBy")
     */
    protected $topics;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Topic", mappedBy="speakers")
     */
    protected $speakingTopics;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Topic", mappedBy="votes")
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
        $this->notifications  = new ArrayCollection();

        $this->notifyByEmail       = false;
        $this->notifyInApplication = true;
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
        return $this->speakingTopics->toArray();
    }

    /**
     * @param Topic $topic
     *
     * @return bool
     */
    public function hasSpeakingTopic(Topic $topic)
    {
        return $this->speakingTopics->contains($topic);
    }

    /**
     * @param Topic $topic
     */
    public function addSpeakingTopic(Topic $topic)
    {
        if (! $this->hasSpeakingTopic($topic)) {
            $this->speakingTopics->add($topic);
        }
    }

    /**
     * @param Topic $topic
     */
    public function removeSpeakingTopic(Topic $topic)
    {
        if ($this->hasSpeakingTopic($topic)) {
            $this->speakingTopics->removeElement($topic);
        }
    }

    /**
     *
     * @return Topic[]
     */
    public function getVotes()
    {
        return $this->votes->toArray();
    }

    /**
     * @param Topic $topic
     *
     * @return bool
     */
    public function hasVoted(Topic $topic)
    {
        return $this->votes->contains($topic);
    }

    /**
     * @param Topic $topic
     */
    public function removeVote(Topic $topic)
    {
        if ($this->hasVoted($topic)) {
            $this->votes->removeElement($topic);
        }
    }

    /**
     * @param Topic $topic
     */
    public function addVote(Topic $topic)
    {
        if (! $this->hasVoted($topic)) {
            $this->votes->add($topic);
        }
    }

    /**
     * @return Notification[]|Collection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @return Notification[]
     */
    public function getUnreadNotifications()
    {
        return $this->notifications->filter(function (Notification $notification) {
            return ! $notification->isDone();
        });
    }

    /**
     *
     * @return Comment[]
     */
    public function getComments()
    {
        return $this->comments;
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

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getPassword()
    {
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }
}
