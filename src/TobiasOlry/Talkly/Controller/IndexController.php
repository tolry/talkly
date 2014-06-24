<?php

namespace TobiasOlry\Talkly\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

use TobiasOlry\Talkly\Entity\Topic;
use TobiasOlry\Talkly\Form\CreateTopicType;

use TobiasOlry\Talkly\Security\Security;

class IndexController
{
    private $twig;
    private $em;
    private $formFactory;
    private $topicRepository;
    private $security;

    public function __construct(
        \Twig_Environment $twig,
        EntityManager $em,
        $formFactory,
        Security $security
    ) {
        $this->twig            = $twig;
        $this->em              = $em;
        $this->formFactory     = $formFactory;
        $this->topicRepository = $this->em->getRepository('TobiasOlry\Talkly\Entity\Topic');
        $this->security        = $security;
    }

    public function dashboard(Request $request)
    {
        $form = $this->formFactory->create(
            new CreateTopicType(),
            new Topic($this->security->getUser())
        );

        $topics          = $this->topicRepository->findNonArchivedMostVotesFirst();
        $lastSubmissions = $this->topicRepository->filterLastSubmissions($topics, $limit = 8);

        return new Response(
            $this->twig->render(
                'index.dashboard.html.twig',
                array(
                    'topics'           => $topics,
                    'last_submissions' => $lastSubmissions,
                    'form'             => $form->createView(),
                )
            )
        );
    }

    public function archive(Request $request)
    {
        $topics = $this->topicRepository->findArchivedGroupByMonth();

        return new Response(
            $this->twig->render(
                'index.archive.html.twig',
                array(
                    'topics' => $topics,
                )
            )
        );
    }
}
