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

    private function getLastSubmissions($limit = 3)
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->select('t, v')
            ->from('TobiasOlry\Talkly\Entity\Topic', 't')
            ->leftJoin('t.votes', 'v')
            ->add('orderBy', 't.createdAt DESC')
        ;

        $topics = $qb->getQuery()->getResult();

        return array_slice($topics, 0, $limit);
    }

    private function getAllTopics()
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->select('t, v')
            ->from('TobiasOlry\Talkly\Entity\Topic', 't')
            ->leftJoin('t.votes', 'v')
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

        return new Response(
            $this->twig->render(
                'index.dashboard.html.twig',
                array(
                    'topics'           => $this->getAllTopics(),
                    'last_submissions' => $this->getLastSubmissions($limit = 5),
                    'form'             => $form->createView(),
                )
            )
        );
    }

}

