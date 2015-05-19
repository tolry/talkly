<?php

namespace TobiasOlry\TalklyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use TobiasOlry\TalklyBundle\Entity\Topic;
use TobiasOlry\TalklyBundle\Form\CreateTopicType;
use TobiasOlry\TalklyBundle\Form\EditTopicType;
use TobiasOlry\TalklyBundle\Form\LectureTopicType;

class TopicController extends Controller
{
    public function createAction(Request $request)
    {
        $topic = new Topic($this->getUser());
        $form  = $this->createForm(new CreateTopicType(), $topic);

        $form->handleRequest($request);
        $this->topicService->add($topic);

        $this->addFlash('topic-' . $topic->getId() . '-success', 'topic created');

        return $this->redirect($topic, 'list');
    }

    /**
     *
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function showAction(Request $request)
    {
        $topic = $this->topicService->getTopic($request->get('id'));
        $form  = $this->createForm(new LectureTopicType(), $topic);

        return [
            'topic' => $topic,
            'form'  => $form->createView()
        ];
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request)
    {
        $topic = $this->topicService->getTopic($request->get('id'));
        $this->topicService->checkUserCanEditTopic($topic, $this->getUser());

        $form = $this->createForm(new EditTopicType(), $topic);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->topicService->update($topic);
            $this->topicService->markAsUpdated($topic);
            $this->addFlash('topic-' . $topic->getId() . '-success', 'topic data updated');

            return $this->redirect($topic, $request->get('view', 'list'));
        }

        return [
            'topic' => $topic,
            'form'  => $form->createView()
        ];
    }

    public function castVoteAction(Request $request)
    {
        $topic = $this->topicService->getTopic($request->get('id'));
        $voter = $this->getUser();

        $this->topicService->addVote($topic, $voter);
        $this->addFlash('topic-' . $topic->getId() . '-success', 'vote cast');

        return $this->redirectView($topic, $request->get('view', 'list'));
    }

    public function retractVoteAction(Request $request)
    {
        $topic = $this->topicService->getTopic($request->get('id'));
        $voter = $this->getUser();

        $this->topicService->removeVote($topic, $voter);

        $this->addFlash('topic-' . $topic->getId() . '-success', 'vote retracted');

        return $this->redirectView($topic, $request->get('view', 'list'));
    }

    public function commentAction(Request $request)
    {
        $topic   = $this->topicService->getTopic($request->get('id'));
        $user    = $this->getUser();
        $comment = $request->get('comment');

        $this->topicService->comment($topic, $user, $comment);

        $this->addFlash('topic-' . $topic->getId() . '-success', 'comment added');

        return $this->redirectView($topic, $request->get('view', 'show'));
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

        $this->addFlash('topic-' . $topic->getId() . '-success', 'lecture updated');

        if ($topic->isLectureHeld()) {
            $this->topicService->markAsHeld($topic);
        } elseif ($dateBefore != $topic->getLectureDate() && null !== $topic->getLectureDate()) {
            $this->topicService->markAsScheduled($topic);
        } elseif ($dateBefore != $topic->getLectureDate() && null === $topic->getLectureDate()) {
            $this->topicService->markAsUnscheduled($topic);
        }

        return $this->redirectView($topic, 'show');
    }

    public function addSpeakerAction(Request $request)
    {
        $topic = $this->topicService->getTopic($request->get('id'));
        $user  = $this->getUser();

        $this->topicService->addSpeaker($topic, $user);

        $this->addFlash('topic-' . $topic->getId() . '-success', 'add you as a speaker');

        return $this->redirectView($topic, $request->get('view', 'list'));
    }


    public function removeSpeakerAction(Request $request)
    {
        $topic = $this->topicService->getTopic($request->get('id'));
        $user  = $this->getUser();

        $this->topicService->removeSpeaker($topic, $user);

        $this->addFlash('topic-' . $topic->getId() . '-success', 'remove you as a speaker');

        return $this->redirectView($topic, $request->get('view', 'list'));
    }

    private function redirectView(Topic $topic, $view = 'show')
    {
        $route = 'homepage';
        if ($topic->isLectureHeld()) {
            $route = 'archive';
        }

        $url = $this->generateUrl($route) . '#topic-' . $topic->getId();

        if ($view == 'show') {
            $route = 'topic-show';
            $url   = $this->generateUrl($route, ['id' => $topic->getId()]);
        }

        return new RedirectResponse($url);
    }
}
