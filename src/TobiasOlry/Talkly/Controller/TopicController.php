<?php

namespace TobiasOlry\Talkly\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

use TobiasOlry\Talkly\Entity\Topic;
use TobiasOlry\Talkly\Form\CreateTopicType;
use TobiasOlry\Talkly\Form\ArchiveTopicType;

use TobiasOlry\Talkly\Security\Security;

class TopicController
{
    private $em;
    private $formFactory;
    private $urlGenerator;
    private $twig;
    private $security;

    public function __construct(
        EntityManager $em,
        $formFactory,
        $urlGenerator,
        \Twig_Environment $twig,
        Security $security
    ) {
        $this->em           = $em;
        $this->formFactory  = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->twig         = $twig;
        $this->security     = $security;
    }

    private function getTopic($id, $allowArchived = false)
    {
        if (empty($id)) {
            // todo
        }

        $topic = $this->em->find('TobiasOlry\Talkly\Entity\Topic', $id);

        if (! $topic) {
            // todo
        }

        if (! $allowArchived && $topic->isArchived()) {
            // todo
        }

        return $topic;
    }

    private function redirect(Topic $topic, $view = 'show')
    {
        $route = 'homepage';
        if ($topic->isArchived()) {
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

    public function create(Request $request)
    {
        $topic = new Topic($this->security->getUser());

        $form = $this->formFactory->create(
            new CreateTopicType(),
            $topic
        );

        $form->handleRequest($request);

        $this->em->persist($topic);
        $this->em->flush();

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'topic created');

        return $this->redirect($topic, 'list');
    }

    public function castVote(Request $request)
    {
        $topic = $this->getTopic($request->get('id'));
        $voter = $this->security->getUser();

        $topic->castVote($voter);
        $this->em->flush();

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'vote cast');

        return $this->redirect($topic, $request->get('view', 'list'));
    }

    public function show(Request $request)
    {
        $topic = $this->getTopic($request->get('id'));

        $form = $this->formFactory->create(
            new ArchiveTopicType(),
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

    public function retractVote(Request $request)
    {
        $topic = $this->getTopic($request->get('id'));
        $voter = $this->security->getUser();

        $vote = $topic->getVote($voter);

        if (! $vote) {

            return $this->talklyJsonResponse(Response::HTTP_BAD_REQUEST, 'retract-vote', array('error' => 'the requested vote did not exist'));
        }

        $this->em->remove($vote);
        $this->em->flush();

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'vote retracted');

        return $this->redirect($topic, $request->get('view', 'list'));
    }

    public function comment(Request $request)
    {
        $topic = $this->getTopic($request->get('id'));
        $topic->comment($this->security->getUser(), $request->get('comment'));

        $this->em->flush();

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'comment added');

        return $this->redirect($topic, $request->get('view', 'show'));
    }

    public function archive(Request $request)
    {
        $topic = $this->getTopic($request->get('id'), $allowArchived = true);

        $form = $this->formFactory->create(
            new ArchiveTopicType(),
            $topic
        );

        $form->handleRequest($request);

        $this->em->flush();

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'lecture entered');

        return $this->redirect($topic, $request->get('view', 'list'));
    }

    public function addSpeakerAction(Request $request)
    {
        $topic = $this->getTopic($request->get('id'));
        $user  = $this->security->getUser();

        if (!$topic->getSpeakers()->contains($user)) {
            $topic->getSpeakers()->add($user);
            $user->getSpeakingTopics()->add($topic);
        }

        $this->em->flush();

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'add you as a speaker');

        return $this->redirect($topic, $request->get('view', 'list'));
    }


    public function removeSpeakerAction(Request $request)
    {
        $topic = $this->getTopic($request->get('id'));
        $user  = $this->security->getUser();

        if ($topic->getSpeakers()->contains($user)) {
            $topic->getSpeakers()->removeElement($user);
            $user->getSpeakingTopics()->removeElement($topic);
        }

        $this->em->flush();

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'remove you as a speaker');

        return $this->redirect($topic, $request->get('view', 'list'));
    }
}
