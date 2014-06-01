<?php

namespace TobiasOlry\Talkly\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    private $twig;

    public function __construct(
        EntityManager $em,
        $formFactory,
        $urlGenerator,
        \Twig_Environment $twig
    )
    {
        $this->em           = $em;
        $this->formFactory  = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->twig         = $twig;
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

    private function redirect(Topic $topic)
    {
        $route = 'homepage';
        if ($topic->isArchived()) {
            $route = 'archive';
        }

        $url = $this->urlGenerator->generate($route)
            . '#topic-' . $topic->getId();

        return new RedirectResponse($url);
    }

    private function talklyJsonResponse($statusCode, $action, array $data)
    {
        $data['action'] = $action;

        return new JsonResponse($data, $statusCode);
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

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'topic created');

        return $this->redirect($topic);
    }

    public function castVote(Request $request)
    {
        $topic = $this->getTopic($request->get('id'));
        $voter = $request->getUser();

        $topic->castVote($voter);
        $this->em->flush();

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'vote cast');

        return $this->redirect($topic);
    }

    public function retractVote(Request $request)
    {
        $topic = $this->getTopic($request->get('id'));
        $voter = $request->getUser();

        $vote = $topic->getVote($voter);

        if (! $vote) {

            return $this->talklyJsonResponse(Response::HTTP_BAD_REQUEST, 'retract-vote', array('error' => 'the requested vote did not exist'));
        }

        $this->em->remove($vote);
        $this->em->flush();

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'vote retracted');

        return $this->redirect($topic);
    }

    public function comment(Request $request)
    {
        $topic = $this->getTopic($request->get('id'));
        $topic->comment($request->getUser(), $request->get('comment'));

        $this->em->flush();

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'comment added');

        return $this->redirect($topic);
    }

    public function archive(Request $request)
    {
        $topic = $this->getTopic($request->get('id'), $allowArchived = true);

        $topic->setLectureDetails(
            new \DateTime($request->get('date')),
            $request->get('user'),
            $request->get('note')
        );

        $this->em->flush();

        $request->getSession()->getFlashBag()->add('topic-' . $topic->getId() . '-success', 'lecture entered');

        return $this->redirect($topic);
    }
}

