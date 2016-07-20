<?php

namespace TobiasOlry\TalklyBundle\Repository;

use Doctrine\ORM\EntityRepository;
use TobiasOlry\TalklyBundle\Criteria\TopicCriteria;
use TobiasOlry\TalklyBundle\Entity\Topic;

/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 * @author David Badura <david.badura@i22.de>
 */
class TopicRepository extends EntityRepository
{
    /**
     * @param TopicCriteria $criteria
     * @return Topic[]
     */
    public function findByCriteria(TopicCriteria $criteria)
    {
        $qb = $this->createQueryBuilder('t');

        $qb
            ->select('t, v, c')
            ->leftJoin('t.votes', 'v')
            ->leftJoin('t.comments', 'c')
            ->orderBy('t.createdAt', 'DESC');

        if ($criteria->archived === false) {
            $qb->andWhere('t.lectureHeld = 0');
        }

        if ($criteria->archived === true) {
            $qb->andWhere('t.lectureHeld = 1');
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Topic[]
     */
    public function findNonArchivedMostVotesFirst()
    {
        $criteria = new TopicCriteria(false);

        $topics = \Pinq\Traversable::from($this->findByCriteria($criteria));

        $result = $topics
            ->orderByDescending(function (Topic $topic) {
                return count($topic->getVotes());
            })->asArray();
        
        return array_values($result);
    }

    /**
     * @return array
     */
    public function findArchivedGroupByMonth()
    {
        $criteria = new TopicCriteria(true);

        $topics = $this->findByCriteria($criteria);

        $result = [];
        foreach ($topics as $topic) {
            if (!$topic->getLectureDate()) {
                continue;
            }

            $key = $topic->getLectureDate() ? $topic->getLectureDate()->format('Y-m') : 'unknown';

            $result[$key][] = $topic;
        }

        return $result;
    }

    /**
     * @return array
     */
    public function findNextGroupByMonth()
    {
        $topics = $this->findByCriteria(new TopicCriteria(false));

        $result = [];
        foreach ($topics as $topic) {
            if (!$topic->getLectureDate()) {
                continue;
            }

            $result[$topic->getLectureDate()->format('Y-m')][] = $topic;
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return \Pinq\ITraversable|\Pinq\Traversable
     */
    public function findNextTopics($limit = 5)
    {
        $criteria = new TopicCriteria(false);

        $topics = \Pinq\Traversable::from($this->findByCriteria($criteria));

        return $topics
            ->where(function (Topic $topic) {
                return $topic->getLectureDate() ? true : false;
            })
            ->orderByAscending(function (Topic $topic) {
                return $topic->getLectureDate();
            })
            ->take($limit);
    }

    /**
     * @param int $limit
     * @return \Pinq\ITraversable|\Pinq\Traversable
     */
    public function findLastSubmissions($limit = 3)
    {
        $criteria = new TopicCriteria(false);

        $topics = \Pinq\Traversable::from($this->findByCriteria($criteria));

        return $topics
            ->orderByDescending(function (Topic $topic) {
                return $topic->getCreatedAt();
            })
            ->take($limit);
    }
}
