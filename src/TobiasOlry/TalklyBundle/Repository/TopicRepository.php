<?php
/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\TalklyBundle\Repository;

use Doctrine\ORM\EntityRepository;
use TobiasOlry\TalklyBundle\Criteria\TopicCriteria;
use TobiasOlry\TalklyBundle\Entity\Topic;

class TopicRepository extends EntityRepository
{
    public function findByCriteria(TopicCriteria $criteria)
    {
        $qb = $this->createQueryBuilder('t');

        $qb
            ->select('t, v, c')
            ->leftJoin('t.votes', 'v')
            ->leftJoin('t.comments', 'c')
            ->add('orderBy', 't.createdAt DESC');

        if ($criteria->archived === false) {
            $qb->andWhere('t.lectureHeld = 0');
        }

        if ($criteria->archived === true) {
            $qb->andWhere('t.lectureHeld = 1');
        }

        return $qb->getQuery()->getResult();
    }

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

    public function findArchivedGroupByMonth()
    {
        $criteria = new TopicCriteria(true);

        $topics = \Pinq\Traversable::from($this->findByCriteria($criteria));

        return $topics
            ->orderByDescending(function (Topic $topic) {
                return $topic->getLectureDate();
            })
            ->groupBy(function (Topic $topic) {
                if ($topic->getLectureDate()) {
                    return $topic->getLectureDate()->format('Y-m');
                }

                return 'unknown';
            });
    }

    public function findNextGroupByMonth()
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
            ->groupBy(function (Topic $topic) {
                return $topic->getLectureDate()->format('Y-m');
            });
    }

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
