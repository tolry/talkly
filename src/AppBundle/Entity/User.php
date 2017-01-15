<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation as JSON;

/**
 * @author David Badura <d.a.badura@gmail.com>
 * @author Tobias Olry <tobias.olry@gmail.com>
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
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
     *
     * @JSON\Groups({"topic_list", "topic_show", "user_show", "user_self"})
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     *
     * @JSON\Groups({"topic_list", "topic_show", "user_show", "user_self"})
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @JSON\Groups({"topic_list", "topic_show", "user_show", "user_self"})
     */
    protected $name;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     *
     * @JSON\Groups({"user_self"})
     */
    protected $active;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @JSON\Groups({"user_self"})
     */
    protected $notifyByEmail;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     *
     * @JSON\Groups({"user_self"})
     */
    protected $notifyInApplication;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @JSON\Groups({"topic_list", "topic_show", "user_show", "user_self"})
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
     * @var ArrayCollection|Topic[]
     *
     * @ORM\ManyToMany(targetEntity="Topic", mappedBy="speakers")
     */
    protected $speakingTopics;

    /**
     * @var ArrayCollection|Topic[]
     *
     * @ORM\ManyToMany(targetEntity="Topic", mappedBy="votes")
     */
    protected $votes;

    /**
     * @param string $username
     */
    public function __construct($username)
    {
        $this->username = $username;

        $this->comments = new ArrayCollection();
        $this->topics = new ArrayCollection();
        $this->lectures = new ArrayCollection();
        $this->votes = new ArrayCollection();
        $this->speakingTopics = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->active = true;

        $this->notifyByEmail = false;
        $this->notifyInApplication = true;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
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
        if (!$this->hasSpeakingTopic($topic)) {
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
        if (!$this->hasVoted($topic)) {
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

    public function isActive()
    {
        return $this->active;
    }

    /**
     * @return Notification[]
     */
    public function getUnreadNotifications()
    {
        return array_values($this->notifications->filter(function (Notification $notification) {
            return !$notification->isDone();
        })->toArray());
    }

    /**
     * @return Comment[]
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function getNotifyByEmail()
    {
        return $this->notifyByEmail;
    }

    /**
     * @param bool $notifyByEmail
     */
    public function setNotifyByEmail($notifyByEmail)
    {
        $this->notifyByEmail = (bool)$notifyByEmail;
    }

    /**
     * @return bool
     */
    public function getNotifyInApplication()
    {
        return $this->notifyInApplication;
    }

    /**
     * @param bool $notifyInApplication
     */
    public function setNotifyInApplication($notifyInApplication)
    {
        $this->notifyInApplication = (bool)$notifyInApplication;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return '';
    }

    /**
     *
     */
    public function eraseCredentials()
    {
        // do nothing
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        if (!empty($this->name)) {
            return $this->name;
        }

        return $this->username;
    }
}
