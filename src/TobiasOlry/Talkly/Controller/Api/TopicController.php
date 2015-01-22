<?php

namespace TobiasOlry\Talkly\Controller\Api;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGenerator;
use TobiasOlry\Talkly\Entity\Topic;
use TobiasOlry\Talkly\Entity\User;
use TobiasOlry\Talkly\Service\TopicService;

class TopicController
{
    /**
     * @var TopicService
     */
    private $topicService;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * @param TopicService $topicService
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(TopicService $topicService, UrlGenerator $urlGenerator)
    {
        $this->topicService = $topicService;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return JsonResponse
     */
    public function listAction()
    {
        $topics = iterator_to_array($this->topicService->findNonArchivedMostVotesFirst());

        $result = (new ArrayCollection($topics))->map(function (Topic $topic) {

            $votes = array_map(function (User $user) {
                return ['id' => $user->getId(), 'name' => (string) $user];
            }, $topic->getVotes()->toArray());

            $speakers = array_map(function (User $user) {
                return ['id' => $user->getId(), 'name' => (string) $user];
            }, $topic->getSpeakers()->toArray());

            $params = ['id' => $topic->getId()];

            return [
                'id'            => $topic->getId(),
                'title'         => $topic->getTitle(),
                'votes'         => $votes,
                'speakers'      => $speakers,
                'comment_count' => count($topic->getComments()),
                'lecture_date'  => $topic->getLectureDate() ? $topic->getLectureDate()->format('Y-m-d') : null,
                'created_at'    => $topic->getCreatedAt() ? $topic->getCreatedAt()->format('Y-m-d') : null,
                '_links'        => [
                    'self' => [
                        'show'           => $this->urlGenerator->generate('topic-show', $params),
                        'edit'           => $this->urlGenerator->generate('topic-edit', $params),
                        'cast_vote'      => $this->urlGenerator->generate('topic-cast-vote', $params),
                        'retract_vote'   => $this->urlGenerator->generate('topic-retract-vote', $params),
                        'add-speaker'    => $this->urlGenerator->generate('topic-add-speaker', $params),
                        'remove-speaker' => $this->urlGenerator->generate('topic-remove-speaker', $params),
                    ]
                ]
            ];
        })->toArray();

        return new JsonResponse(array_values($result));
    }
}