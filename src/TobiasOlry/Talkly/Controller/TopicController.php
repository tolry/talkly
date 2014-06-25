<?php

namespace TobiasOlry\Talkly\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

use TobiasOlry\Talkly\Entity\Topic;
use TobiasOlry\Talkly\Form\CreateTopicType;
use TobiasOlry\Talkly\Form\LectureTopicType;

use TobiasOlry\Talkly\Security\Security;

class TopicController
{
    private $formFactory;
    private $urlGenerator;
    private $twig;
    private $security;
    private $topicService;

    public function __construct(
        $topicService,
        $formFactory,
        $urlGenerator,
        \Twig_Environment $twig,
        Security $security
    ) {
        $this->topicService = $topicService;
        $this->formFactory  = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->twig         = $twig;
        $this->security     = $security;
    }

    public function createAction(Request $request)
    {
        $topic = new Topic($this->security->getUser());

        $form = $this->formFactory->create(
            new CreateTopicType(),
            $topic
        );

        $form->handleRequest($request);

        $this->topicService->save($topic);

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'topic created');

        return $this->redirect($topic, 'list');
    }

    public function castVoteAction(Request $request)
    {
        $topic = $this->topicService->getTopic($request->get('id'));
        $voter = $this->security->getUser();

        $this->topicService->castVote($topic, $voter);

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'vote cast');

        return $this->redirect($topic, $request->get('view', 'list'));
    }

    public function showAction(Request $request)
    {
        $topic = $this->topicService->getTopic($request->get('id'));

        $form = $this->formFactory->create(
            new LectureTopicType(),
            $topic
        );

        return new Response(
            $this->twig->render(
                'topic/show.html.twig',
                array(
                    'topic' => $topic,
                    'form'  => $form->createView()
                )
            )
        );
    }

    public function retractVoteAction(Request $request)
    {
        $topic = $this->topicService->getTopic($request->get('id'));
        $voter = $this->security->getUser();

        $vote = $topic->getVote($voter);

        if (! $vote) {

            return $this->talklyJsonResponse(Response::HTTP_BAD_REQUEST, 'retract-vote', array('error' => 'the requested vote did not exist'));
        }

        $this->topicService->removeVote($vote);

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'vote retracted');

        return $this->redirect($topic, $request->get('view', 'list'));
    }

    public function commentAction(Request $request)
    {
        $topic   = $this->topicService->getTopic($request->get('id'));
        $user    = $this->security->getUser();
        $comment = $request->get('comment');

        $this->topicService->comment($topic, $user, $comment);

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'comment added');

        return $this->redirect($topic, $request->get('view', 'show'));
    }

    public function archiveAction(Request $request)
    {
        $topic = $this->topicService->getTopic($request->get('id'), $allowArchived = true);

        $form = $this->formFactory->create(
            new LectureTopicType(),
            $topic
        );

        $form->handleRequest($request);

        $this->topicService->save($topic);

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'lecture updated');

        return $this->redirect($topic, $request->get('view', 'list'));
    }

    public function addSpeakerAction(Request $request)
    {
        $topic = $this->topicService->getTopic($request->get('id'));
        $user  = $this->security->getUser();

        $this->topicService->addSpeaker($topic, $user);

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'add you as a speaker');

        return $this->redirect($topic, $request->get('view', 'list'));
    }


    public function removeSpeakerAction(Request $request)
    {
        $topic = $this->topicService->getTopic($request->get('id'));
        $user  = $this->security->getUser();

        $this->topicService->removeSpeaker($topic, $user);

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'remove you as a speaker');

        return $this->redirect($topic, $request->get('view', 'list'));
    }

    private function redirect(Topic $topic, $view = 'show')
    {
        $route = 'homepage';
        if ($topic->isLectureHeld()) {
            $route = 'archive';
        }

        $url = $this->urlGenerator->generate($route)
            . '#topic-' . $topic->getId();

        if ($view == 'show') {
            $route = 'topic-show';
            $url = $this->urlGenerator
                ->generate($route, array('id' => $topic->getId()));
        }

        return new RedirectResponse($url);
    }

    private function talklyJsonResponse($statusCode, $action, array $data)
    {
        $data['action'] = $action;

        return new JsonResponse($data, $statusCode);
    }
}
