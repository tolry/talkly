<?php

namespace TobiasOlry\Talkly\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

use TobiasOlry\Talkly\Entity\Topic;
use TobiasOlry\Talkly\Form\CreateTopicType;

class TopicController
{
    private $em;
    private $formFactory;
    private $urlGenerator;

    public function __construct(EntityManager $em, $formFactory, $urlGenerator)
    {
        $this->em           = $em;
        $this->formFactory  = $formFactory;
        $this->urlGenerator = $urlGenerator;
    }

    public function create(Request $request)
    {
        $topic = new Topic($request->getUser());

        $form = $this->formFactory->create(
            new CreateTopicType(),
            $topic
        );

        $form->handleRequest($request);

        $this->em->persist($topic);
        $this->em->flush();

        $url = $this->urlGenerator->generate('homepage') . '#topic-' . $topic->getId();

        return new RedirectResponse($url);
    }
}

