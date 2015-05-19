<?php

namespace TobiasOlry\TalklyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TobiasOlry\TalklyBundle\Entity\Topic;
use TobiasOlry\TalklyBundle\Form\CreateTopicType;

class IndexController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     *
     * @return Response
     */
    public function dashboardAction()
    {
        $form = $this->createForm(
            new CreateTopicType(),
            new Topic($this->getUser())
        );

        $topics          = [];//$this->topicService->findNonArchivedMostVotesFirst();
        $lastSubmissions = [];//$this->topicService->findLastSubmissions($limit = 5);
        $nextTopics      = [];//$this->topicService->findNextTopics($limit = 5);

        return [
            'topics'           => $topics,
            'last_submissions' => $lastSubmissions,
            'next_topics'      => $nextTopics,
            'form'             => $form->createView(),
        ];
    }

    public function archiveAction(Request $request)
    {
        $topics = $this->topicService->findArchivedGroupByMonth();

        return new Response(
            $this->twig->render(
                'index/archive.html.twig',
                ['topics' => $topics]
            )
        );
    }

    public function calendarAction(Request $request)
    {
        $topics = $this->topicService->findNextGroupByMonth();

        return new Response(
            $this->twig->render(
                'index/calendar.html.twig',
                ['topics' => $topics]
            )
        );
    }
}
