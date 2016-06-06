<?php

namespace TobiasOlry\TalklyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use TobiasOlry\TalklyBundle\Entity\Topic;
use TobiasOlry\TalklyBundle\Form\CreateTopicType;
use TobiasOlry\TalklyBundle\Form\EditTopicType;
use TobiasOlry\TalklyBundle\Form\LectureTopicType;

/**
 * @Route("/topic")
 */
class TopicController extends Controller
{
    /**
     * @Route("/", name="topics")
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $repository = $this->get('talkly.repository.topic');
        $topics     = $repository->findNonArchivedMostVotesFirst();


        $serializer = $this->get('jms_serializer');
        $json = $serializer->serialize($topics, 'json');

        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }


    /**
     * @Route("/create", name="topic-create")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function createAction(Request $request)
    {
        $topic = new Topic($this->getUser());
        $form  = $this->createForm(new CreateTopicType(), $topic);

        $form->handleRequest($request);
        $this->getTopicService()->add($topic);

        $this->addFlash('topic-' . $topic->getId() . '-success', 'topic created');

        return $this->redirectToView($topic, 'show');
    }

    /**
     * @Route("/{id}/", name="topic-show")
     * @Route("/{id}/show", name="topic-show-legacy")
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function showAction(Request $request)
    {
        $topic = $this->getTopicService()->getTopic($request->get('id'));

        $serializer = $this->get('jms_serializer');
        $json = $serializer->serialize($topic, 'json');

        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="topic-edit")
     * @Template()
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request)
    {
        $service = $this->getTopicService();
        $topic   = $service->getTopic($request->get('id'));
        $service->checkUserCanEditTopic($topic, $this->getUser());

        $form = $this->createForm(new EditTopicType(), $topic);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $service->update($topic);
            $service->markAsUpdated($topic);
            $this->addFlash('topic-' . $topic->getId() . '-success', 'topic data updated');

            return $this->redirectToView($topic, $request->get('view', 'list'));
        }

        return ['topic' => $topic, 'form' => $form->createView()];
    }

    /**
     * @Route("/{id}/cast-vote", name="topic-cast-vote")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function castVoteAction(Request $request)
    {
        $service = $this->getTopicService();
        $topic   = $service->getTopic($request->get('id'));

        $service->addVote($topic, $this->getUser());

        return new JsonResponse('success');
    }

    /**
     * @Route("/{id}/retract-vote", name="topic-retract-vote")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function retractVoteAction(Request $request)
    {
        $service = $this->getTopicService();
        $topic   = $service->getTopic($request->get('id'));

        $service->removeVote($topic, $this->getUser());
        $this->addFlash('topic-' . $topic->getId() . '-success', 'vote retracted');

        return new JsonResponse('success');
    }

    /**
     * @Route("/{id}/comment", name="topic-comment")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function commentAction(Request $request)
    {
        $service = $this->getTopicService();
        $topic   = $service->getTopic($request->get('id'));
        $user    = $this->getUser();
        $comment = $request->get('comment');

        $service->comment($topic, $user, $comment);
        $this->addFlash('topic-' . $topic->getId() . '-success', 'comment added');

        return $this->redirectToView($topic, $request->get('view', 'show'));
    }

    /**
     * @Route("/{id}/archive", name="topic-archive")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function archiveAction(Request $request)
    {
        $service    = $this->getTopicService();
        $topic      = $service->getTopic($request->get('id'), $allowArchived = true);
        $dateBefore = $topic->getLectureDate();

        $form = $this->createForm(new LectureTopicType(), $topic);

        $form->handleRequest($request);
        $service->update($topic);

        $this->addFlash('topic-' . $topic->getId() . '-success', 'lecture updated');

        if ($topic->isLectureHeld()) {
            $service->markAsHeld($topic);
        } elseif ($dateBefore != $topic->getLectureDate() && null !== $topic->getLectureDate()) {
            $service->markAsScheduled($topic);
        } elseif ($dateBefore != $topic->getLectureDate() && null === $topic->getLectureDate()) {
            $service->markAsUnscheduled($topic);
        }

        return $this->redirectToView($topic, 'show');
    }

    /**
     * @Route("/{id}/add-speaker", name="topic-add-speaker")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function addSpeakerAction(Request $request)
    {
        $service = $this->getTopicService();
        $topic   = $service->getTopic($request->get('id'));

        $service->addSpeaker($topic, $this->getUser());
        $this->addFlash('topic-' . $topic->getId() . '-success', 'add you as a speaker');

        return new JsonResponse('success');
    }

    /**
     * @Route("/{id}/remove-speaker", name="topic-remove-speaker")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function removeSpeakerAction(Request $request)
    {
        $service = $this->getTopicService();
        $topic   = $service->getTopic($request->get('id'));

        $service->removeSpeaker($topic, $this->getUser());
        $this->addFlash('topic-' . $topic->getId() . '-success', 'remove you as a speaker');

        return new JsonResponse('success');
    }

    /**
     * @param Topic  $topic
     * @param string $view
     *
     * @return RedirectResponse
     */
    private function redirectToView(Topic $topic, $view = 'show')
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

    /**
     * @return \TobiasOlry\TalklyBundle\Service\TopicService
     */
    protected function getTopicService()
    {
        return $this->get('talkly.service.topic');
    }
}
