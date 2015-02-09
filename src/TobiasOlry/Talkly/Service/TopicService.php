<?php

namespace TobiasOlry\Talkly\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TobiasOlry\Talkly\Entity\Topic;
use TobiasOlry\Talkly\Entity\User;
use TobiasOlry\Talkly\Event\CommentEvent;
use TobiasOlry\Talkly\Event\Events;
use TobiasOlry\Talkly\Event\TopicEvent;
use TobiasOlry\Talkly\Repository\TopicRepository;

/**
 *
 * @author Daniel Badura <d.m.badura@gmail.com>
 */
class TopicService
{
    private $em;
    private $eventDispatcher;

    /**
     * @var TopicRepository
     */
    private $topicRepository;

    public function __construct(EntityManager $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em              = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->topicRepository = $em->getRepository('TobiasOlry\Talkly\Entity\Topic');
    }

    public function checkUserCanEditTopic(Topic $topic, User $user)
    {
        if ($topic->getCreatedBy() != $user) {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @param int  $id
     * @param bool $allowArchived
     *
     * @return null|Topic
     */
    public function getTopic($id, $allowArchived = false)
    {
        if (empty($id)) {
            throw new NotFoundHttpException();
        }

        $topic = $this->topicRepository->find($id);

        if (! $topic) {
            throw new NotFoundHttpException();
        }

        if (! $allowArchived && $topic->isLectureHeld()) {
            // throw new AccessDeniedHttpException();
        }

        return $topic;
    }

    /**
     *
     * @param Topic $topic
     * @param User  $user
     */
    public function addVote(Topic $topic, User $user)
    {
        if ($topic->hasVote($user)) {
            return;
        }

        $topic->addVote($user);
        $user->addVote($topic);

        $this->em->flush();
    }

    /**
     *
     * @param Topic $topic
     * @param User  $user
     */
    public function removeVote(Topic $topic, User $user)
    {
        $topic->removeVote($user);
        $user->removeVote($topic);

        $this->em->flush();
    }

    public function addSpeaker(Topic $topic, User $user)
    {
        if ($topic->hasSpeaker($user)) {
            return;
        }

        $topic->addSpeaker($user);
        $user->addSpeakingTopic($topic);

        $this->em->flush();

        $this->eventDispatcher->dispatch(Events::TOPIC_SPEAKER_FOUND, new TopicEvent($topic));
    }

    /**
     * @param Topic $topic
     * @param User  $user
     */
    public function removeSpeaker(Topic $topic, User $user)
    {
        $topic->removeSpeaker($user);
        $user->removeSpeakingTopic($topic);

        $this->em->flush();
    }

    public function comment(Topic $topic, User $user, $comment)
    {
        $comment = $topic->comment($user, $comment);
        $this->em->flush();

        $this->eventDispatcher->dispatch(
            Events::COMMENT_CREATED,
            new CommentEvent($comment)
        );
    }

    public function markAsUpdated(Topic $topic)
    {
        $this->eventDispatcher->dispatch(
            Events::TOPIC_UPDATED,
            new TopicEvent($topic)
        );
    }

    public function markAsHeld(Topic $topic)
    {
        $this->eventDispatcher->dispatch(
            Events::TOPIC_TALK_HELD,
            new TopicEvent($topic)
        );
    }

    public function markAsScheduled(Topic $topic)
    {
        $this->eventDispatcher->dispatch(
            Events::TOPIC_TALK_SCHEDULED,
            new TopicEvent($topic)
        );
    }

    public function markAsUnscheduled(Topic $topic)
    {
        $this->eventDispatcher->dispatch(Events::TOPIC_TALK_UNSCHEDULED, new TopicEvent($topic));
    }

    /**
     * @return array|Topic[]
     */
    public function findNonArchivedMostVotesFirst()
    {
        return $this->topicRepository->findNonArchivedMostVotesFirst();
    }

    public function findLastSubmissions($limit = 8)
    {
        return $this->topicRepository->findLastSubmissions($limit);
    }

    public function findArchivedGroupByMonth()
    {
        return $this->topicRepository->findArchivedGroupByMonth();
    }

    public function findNextTopics($limit = 5)
    {
        return $this->topicRepository->findNextTopics($limit);
    }

    public function findNextGroupByMonth()
    {
        return $this->topicRepository->findNextGroupByMonth();
    }

    public function findAllParticipants(Topic $topic)
    {
        return array_unique(array_merge(
            $topic->getVotes(),
            $topic->getCommentingUsers(),
            $topic->getSpeakers(),
            [$topic->getCreatedBy()]
        ));
    }

    public function add(Topic $topic)
    {
        $this->em->persist($topic);
        $this->em->flush();

        $this->eventDispatcher->dispatch(
            Events::TOPIC_CREATED,
            new TopicEvent($topic)
        );
    }

    public function update(Topic $topic)
    {
        $this->em->flush($topic);
    }
}
