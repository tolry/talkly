<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Topic;
use Doctrine\ORM\EntityRepository;

/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 * @author David Badura <david.badura@i22.de>
 */
class TopicRepository extends EntityRepository
{
    /**
     * @return Topic[]
     */
    public function findNonArchivedMostVotesFirst()
    {
        return $this->createQueryBuilder('t')
            ->select('t, v, c')
            ->leftJoin('t.votes', 'v')
            ->leftJoin('t.comments', 'c')
            ->orderBy('SIZE(t.votes)', 'DESC')
            ->andWhere('t.lectureHeld = false')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function findArchivedGroupByMonth()
    {
        /** @var Topic[] $topics */
        $topics = $this->createQueryBuilder('t')
            ->select('t, v, c')
            ->leftJoin('t.votes', 'v')
            ->leftJoin('t.comments', 'c')
            ->orderBy('t.lectureDate', 'DESC')
            ->andWhere('t.lectureHeld = true')
            ->getQuery()
            ->getResult();

        $result = [];

        foreach ($topics as $topic) {
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
        /** @var Topic[] $topics */
        $topics = $this->createQueryBuilder('t')
            ->select('t, v, c')
            ->leftJoin('t.votes', 'v')
            ->leftJoin('t.comments', 'c')
            ->orderBy('t.lectureDate', 'ASC')
            ->andWhere('t.lectureHeld = false')
            ->andWhere('t.lectureDate IS NOT NULL')
            ->getQuery()
            ->getResult();

        $result = [];
        foreach ($topics as $topic) {
            $result[$topic->getLectureDate()->format('Y-m')][] = $topic;
        }

        return $result;
    }
}
