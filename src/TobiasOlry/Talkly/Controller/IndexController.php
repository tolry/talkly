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

    public function __construct(
        \Twig_Environment $twig,
        EntityManager $em,
        $formFactory
    )
    {
        $this->twig         = $twig;
        $this->em           = $em;
        $this->formFactory  = $formFactory;
    }

    private function getLastSubmissions($topics, $limit = 3)
    {
        $topics = \Pinq\Traversable::from($topics);

        return $topics
            ->orderByDescending(function($topic) { return $topic->getCreatedAt(); })
            ->take($limit)
        ;
    }

    private function getAllTopics()
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->select('t, v, c')
            ->from('TobiasOlry\Talkly\Entity\Topic', 't')
            ->leftJoin('t.votes', 'v')
            ->leftJoin('t.comments', 'c')
            ->add('orderBy', 't.createdAt DESC')
        ;

        $topics = $qb->getQuery()->getResult();
        usort($topics, function($a, $b) {
            return $b->getVotes()->count() - $a->getVotes()->count();
        });

        return $topics;
    }

    public function dashboard(Request $request)
    {
        $form = $this->formFactory->create(
            new CreateTopicType(),
            new Topic($request->getUser())
        );

        $topics = $this->getAllTopics();

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

}

