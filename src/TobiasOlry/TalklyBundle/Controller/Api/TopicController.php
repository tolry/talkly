<?php

namespace TobiasOlry\TalklyBundle\Controller\Api;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use TobiasOlry\TalklyBundle\Entity\Topic;
use TobiasOlry\TalklyBundle\Entity\User;
use TobiasOlry\TalklyBundle\Form\LectureTopicType;

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
        $twig       = $this->get('templating');

        $result = array_map(function (Topic $topic) use ($twig) {

            $form = $this->createForm(new LectureTopicType(), $topic);

            return [
                'id'           => $topic->getId(),
                'title'        => $topic->getTitle(),
                'votes'        => count($topic->getVotes()),
                'speakers'     => count($topic->getSpeakers()) > 0,
                'comments'     => count($topic->getComments()),
                'lecture_date' => $topic->getLectureDate() ? $topic->getLectureDate()->format('Y-m-d') : null,
                'created_at'   => $topic->getCreatedAt() ? $topic->getCreatedAt()->format('Y-m-d') : null,
                'created_by'   => (string) $topic->getCreatedBy(),
                'html'         => [
                    'short' => $twig->render('TobiasOlryTalklyBundle:Topic:display.short.html.twig', ['topic' => $topic]),
                    'long' => $twig->render('TobiasOlryTalklyBundle:Topic:display.long.html.twig', ['topic' => $topic, 'form' => $form->createView()]),
                ],
            ];
        }, $topics);

        return new JsonResponse(array_values($result));
    }
}
