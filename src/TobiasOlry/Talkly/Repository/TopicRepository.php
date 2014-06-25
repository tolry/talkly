<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Repository;

use Doctrine\ORM\EntityRepository;
use TobiasOlry\Talkly\Criteria\TopicCriteria;

class TopicRepository extends EntityRepository
{
    public function findByCriteria(TopicCriteria $criteria)
    {
        $qb = $this->createQueryBuilder('t');

        $qb
            ->select('t, v, c')
            ->leftJoin('t.votes', 'v')
            ->leftJoin('t.comments', 'c')
            ->add('orderBy', 't.createdAt DESC')
        ;

        if ($criteria->archived === false) {
            $qb->andWhere('t.lectureDate IS NULL');
        }

        if ($criteria->archived === true) {
            $qb->andWhere('t.lectureDate IS NOT NULL');
        }

        return $qb->getQuery()->getResult();
    }

    public function findNonArchivedMostVotesFirst()
    {
        $criteria = new TopicCriteria();
        $criteria->archived = false;

        $topics = \Pinq\Traversable::from($this->findByCriteria($criteria));

        return $topics
            ->orderByDescending(function($topic) { return $topic->getVotes()->count(); })
        ;
    }

    public function findArchivedGroupByMonth()
    {
        $criteria = new TopicCriteria();
        $criteria->archived = true;

        $topics = \Pinq\Traversable::from($this->findByCriteria($criteria));

        return $topics
            ->orderByDescending(function($topic) { return $topic->getLectureDate(); })
            ->groupBy(function($topic) { return $topic->getLectureDate()->format('Y-m'); })
        ;
    }

    public function findLastSubmissions($limit = 3)
    {
        $criteria = new TopicCriteria();
        $criteria->archived = false;

        $topics = \Pinq\Traversable::from($this->findByCriteria($criteria));

        return $topics
            ->orderByDescending(function($topic) { return $topic->getCreatedAt(); })
            ->take($limit)
        ;
    }

}

