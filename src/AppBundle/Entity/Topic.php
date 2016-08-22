<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as JSON;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TopicRepository")
 * @ORM\Table()
 */
class Topic
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     *
     * @JSON\Groups({"topic_list", "topic_show"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="topics")
     *
     * @JSON\Groups({"topic_list", "topic_show"})
     */
    private $createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @JSON\Groups({"topic_list", "topic_show"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @JSON\Groups({"topic_list", "topic_show"})
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @JSON\Groups({"topic_list", "topic_show"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @JSON\Groups({"topic_list", "topic_show"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="votes")
     * @ORM\JoinTable(name="vote")
     *
     * @JSON\Groups({"topic_list", "topic_show"})
     */
    private $votes;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="topic", cascade="all")
     *
     * @JSON\Groups({"topic_list", "topic_show"})
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="speakingTopics")
     * @ORM\JoinTable(name="speaker")
     *
     * @JSON\Groups({"topic_list", "topic_show"})
     */
    private $speakers;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @JSON\Groups({"topic_show"})
     */
    private $lectureNote;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @JSON\Groups({"topic_list", "topic_show"})
     */
    private $lectureFrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @JSON\Groups({"topic_list", "topic_show"})
     */
    private $lectureUntil;

    /**
     *
     * @ORM\Column(type="boolean")
     *
     * @JSON\Groups({"topic_list", "topic_show"})
     */
    private $lectureHeld;

    /**
     *
     * @param User $user
     */
    public function __construct(User $user = null)
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
     * @return User[]
     */
    public function getVotes()
    {
        return $this->votes->toArray();
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function hasVote(User $user)
    {
        return $this->votes->contains($user);
    }

    /**
     * @param User $user
     */
    public function removeVote(User $user)
    {
        if ($this->hasVote($user)) {
            $this->votes->removeElement($user);
        }
    }

    /**
     * @param User $user
     */
    public function addVote(User $user)
    {
        if (! $this->hasVote($user)) {
            $this->votes->add($user);
        }
    }

    /**
     * @return Comment[]|ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return Comment[]|ArrayCollection
     */
    public function getFeedbackComments()
    {
        return $this->comments->filter(function (Comment $comment) {
            return $comment->isFeedback();
        });
    }

    public function getCommentingUsers()
    {
        $users = [];
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
        return $this->speakers->toArray();
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function hasSpeaker(User $user)
    {
        return $this->speakers->contains($user);
    }

    /**
     * @param User $user
     */
    public function addSpeaker(User $user)
    {
        if (! $this->hasSpeaker($user)) {
            $this->speakers->add($user);
        }
    }

    /**
     * @param User $user
     */
    public function removeSpeaker(User $user)
    {
        if ($this->hasSpeaker($user)) {
            $this->speakers->removeElement($user);
        }
    }

    /**
     * @param User   $user
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
     * @param User $createdBy
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;
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
    public function setLectureFrom(\DateTime $date = null)
    {
        $this->lectureFrom = $date;
    }

    /**
     *
     * @return \DateTime
     */
    public function getLectureFrom()
    {
        return $this->lectureFrom;
    }


    /**
     *
     * @param \DateTime $date
     */
    public function setLectureUntil(\DateTime $date = null)
    {
        $this->lectureUntil = $date;
    }

    /**
     *
     * @return \DateTime
     */
    public function getLectureUntil()
    {
        return $this->lectureUntil;
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

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
