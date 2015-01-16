<?php

namespace TobiasOlry\Talkly\Controller;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use TobiasOlry\Talkly\Entity\Topic;
use TobiasOlry\Talkly\Form\CreateTopicType;
use TobiasOlry\Talkly\Form\EditTopicType;
use TobiasOlry\Talkly\Form\LectureTopicType;
use TobiasOlry\Talkly\Security\Security;
use TobiasOlry\Talkly\Service\TopicService;

class TopicController
{
    private $formFactory;
    private $urlGenerator;
    private $twig;
    private $security;
    private $topicService;

    public function __construct(
        TopicService $topicService,
        FormFactoryInterface $formFactory,
        UrlGenerator $urlGenerator,
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
        $this->topicService->add($topic);

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'topic created');

        return $this->redirect($topic, 'list');
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
                [
                    'topic' => $topic,
                    'form'  => $form->createView()
                ]
            )
        );
    }

    public function editAction(Request $request)
    {
        $topic = $this->topicService->getTopic($request->get('id'));
        $this->topicService->checkUserCanEditTopic($topic, $this->security->getUser());

        $form = $this->formFactory->create(
            new EditTopicType(),
            $topic
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->topicService->update($topic);
            $this->topicService->markAsUpdated($topic);
            $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'topic data updated');

            return $this->redirect($topic, $request->get('view', 'list'));
        }

        return new Response(
            $this->twig->render(
                'topic/edit.html.twig',
                [
                    'topic' => $topic,
                    'form'  => $form->createView()
                ]
            )
        );
    }

    public function castVoteAction(Request $request)
    {
        $topic = $this->topicService->getTopic($request->get('id'));
        $voter = $this->security->getUser();

        $this->topicService->addVote($topic, $voter);

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'vote cast');

        return $this->redirect($topic, $request->get('view', 'list'));
    }

    public function retractVoteAction(Request $request)
    {
        $topic = $this->topicService->getTopic($request->get('id'));
        $voter = $this->security->getUser();

        $this->topicService->removeVote($topic, $voter);

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
        $topic      = $this->topicService->getTopic($request->get('id'), $allowArchived = true);
        $dateBefore = $topic->getLectureDate();

        $form = $this->formFactory->create(
            new LectureTopicType(),
            $topic
        );

        $form->handleRequest($request);
        $this->topicService->update($topic);

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'lecture updated');

        if ($topic->isLectureHeld()) {
            $this->topicService->markAsHeld($topic);
        } elseif ($dateBefore != $topic->getLectureDate()) {
            $this->topicService->markAsScheduled($topic);
        }

        return $this->redirect($topic, 'show');
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

        $url = $this->urlGenerator->generate($route) . '#topic-' . $topic->getId();

        if ($view == 'show') {
            $route = 'topic-show';
            $url   = $this->urlGenerator->generate($route, ['id' => $topic->getId()]);
        }

        return new RedirectResponse($url);
    }
}
