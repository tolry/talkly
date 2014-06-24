<?php

namespace TobiasOlry\Talkly\Service;

use TobiasOlry\Talkly\Entity\User;
use TobiasOlry\Talkly\Entity\Topic;

use Doctrine\ORM\EntityManager;

/**
 *
 * @author Daniel Badura <d.m.badura@gmail.com>
 */
class TopicService
{

    /**
     *
     */
    protected $em;

    /**
     *
     */
    protected $topicRepository;

    /**
     *
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->topicRepository = $em->getRepository('TobiasOlry\Talkly\Entity\Topic');
    }

    public function getTopic($id, $allowArchived = false)
    {
        if (empty($id)) {
            // todo
        }

        $topic = $this->topicRepository->find($id);

        if (! $topic) {
            // todo
        }

        if (! $allowArchived && $topic->isArchived()) {
            // todo
        }

        return $topic;
    }

    public function castVote(Topic $topic, User $voter)
    {
        $topic->castVote($voter);
        $this->em->flush();
    }

    public function removeVote($vote)
    {
        $this->em->remove($vote);
        $this->em->flush();
    }

    public function comment(Topic $topic, User $user, $comment)
    {
        $topic->comment($user, $comment);
        $this->em->flush();
    }

    public function addSpeaker(Topic $topic, User $user)
    {
        if (!$topic->getSpeakers()->contains($user)) {
            $topic->getSpeakers()->add($user);
            $user->getSpeakingTopics()->add($topic);
        }

        $this->em->flush();
    }

    public function removeSpeaker(Topic $topic, User $user)
    {
        if ($topic->getSpeakers()->contains($user)) {
            $topic->getSpeakers()->removeElement($user);
            $user->getSpeakingTopics()->removeElement($topic);
        }

        $this->em->flush();
    }

    public function findNonArchivedMostVotesFirst()
    {
        return $this->topicRepository->findNonArchivedMostVotesFirst();
    }

    public function findLastSubmissions($limit = 8)
    {
        return $this->topicRepository->findLastSubmissions($limit = 8);
    }

    public function findArchivedGroupByMonth()
    {
        return $this->topicRepository->findArchivedGroupByMonth();
    }

    public function save(Topic $topic)
    {
        $this->em->persist($topic);
        $this->em->flush();
    }

}
