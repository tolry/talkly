<?php

namespace TobiasOlry\Talkly\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

use TobiasOlry\Talkly\Entity\Topic;
use TobiasOlry\Talkly\Form\CreateTopicType;

class IndexController
{
    private $twig;
    private $em;
    private $formFactory;
    private $topicRepository;

    public function __construct(
        \Twig_Environment $twig,
        EntityManager $em,
        $formFactory
    )
    {
        $this->twig            = $twig;
        $this->em              = $em;
        $this->formFactory     = $formFactory;
        $this->topicRepository = $this->em->getRepository('TobiasOlry\Talkly\Entity\Topic');
    }

    private function getLastSubmissions($topics, $limit = 3)
    {
        $topics = \Pinq\Traversable::from($topics);

        return $topics
            ->orderByDescending(function($topic) { return $topic->getCreatedAt(); })
            ->take($limit)
        ;
    }

    public function dashboard(Request $request)
    {
        $form = $this->formFactory->create(
            new CreateTopicType(),
            new Topic($request->getUser())
        );

        $topics = $this->topicRepository->findNonArchivedMostVotesFirst();

        return new Response(
            $this->twig->render(
                'index.dashboard.html.twig',
                array(
                    'topics'           => $topics,
                    'last_submissions' => $this->getLastSubmissions($topics, $limit = 3),
                    'form'             => $form->createView(),
                )
            )
        );
    }

    public function archive(Request $request)
    {
        $topics = $this->getAllTopics();

        return new Response(
            $this->twig->render(
                'index.archive.html.twig',
                array(
                    'topics'           => $topics,
                    'last_submissions' => $this->getLastSubmissions($topics, $limit = 3),
                    'form'             => $form->createView(),
                )
            )
        );
    }

}

