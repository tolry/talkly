<?php

namespace TobiasOlry\TalklyBundle\Controller\Api;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use TobiasOlry\TalklyBundle\Entity\Topic;
use TobiasOlry\TalklyBundle\Entity\User;

class TopicController extends Controller
{
    /**
     * @Route("/api/topics/", name="api_topics")
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $repository = $this->get('talkly.repository.topic');
        $topics     = iterator_to_array($repository->findNonArchivedMostVotesFirst());

        $result = (new ArrayCollection($topics))->map(function (Topic $topic) {

            $votes = array_map(function (User $user) {
                return ['id' => $user->getId(), 'name' => (string) $user];
            }, $topic->getVotes());

            $speakers = array_map(function (User $user) {
                return ['id' => $user->getId(), 'name' => (string) $user];
            }, $topic->getSpeakers());

            $params = ['id' => $topic->getId()];

            return [
                'id'            => $topic->getId(),
                'title'         => $topic->getTitle(),
                'votes'         => $votes,
                'speakers'      => $speakers,
                'comment_count' => count($topic->getComments()),
                'lecture_date'  => $topic->getLectureDate() ? $topic->getLectureDate()->format('Y-m-d') : null,
                'created_at'    => $topic->getCreatedAt() ? $topic->getCreatedAt()->format('Y-m-d') : null,
                'created_by'    => (int) $topic->getCreatedBy()->getId(),
                '_links'        => [
                    'self' => [
                        'show'           => $this->generateUrl('topic-show', $params),
                        'edit'           => $this->generateUrl('topic-edit', $params),
                        'cast_vote'      => $this->generateUrl('topic-cast-vote', $params),
                        'retract_vote'   => $this->generateUrl('topic-retract-vote', $params),
                        'add_speaker'    => $this->generateUrl('topic-add-speaker', $params),
                        'remove_speaker' => $this->generateUrl('topic-remove-speaker', $params),
                    ]
                ]
            ];
        })->toArray();

        return new JsonResponse(array_values($result));
    }
}