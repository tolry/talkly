<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Topic;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/topic")
 */
class TopicController extends Controller
{
    /**
     * @Route("/", name="topics")
     *
     * @return Response
     */
    public function listAction()
    {
        $repository = $this->get('talkly.repository.topic');
        $topics = $repository->findNonArchivedMostVotesFirst();

        return $this->json($topics, 200, [], ['groups' => ['topic_list']]);
    }

    /**
     * @Route("/create", name="topic-create")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        /** @var Topic $topic */
        $topic = $this->get('serializer')->deserialize(
            $request->getContent(),
            Topic::class,
            'json'
        );

        $topic->setCreatedBy($this->getUser());

        $this->getTopicService()->add($topic);

        return $this->json($topic, 200, [], ['groups' => ['topic_show']]);
    }

    /**
     * @Route("/{id}/", name="topic-show")
     * @Route("/{id}/show", name="topic-show-legacy")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function showAction(Request $request)
    {
        $topic = $this->getTopicService()->getTopic($request->get('id'));

        return $this->json($topic, 200, [], ['groups' => ['topic_show']]);
    }

    /**
     * @Route("/{id}/edit", name="topic-edit")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Request $request)
    {
        $service = $this->getTopicService();

        $topic = $service->getTopic($request->get('id'));

        $this->get('serializer')->deserialize(
            $request->getContent(),
            Topic::class,
            'json',
            ['object_to_populate' => $topic]
        );

        $service->update($topic);
        $service->markAsUpdated($topic);

        return $this->json($topic, 200, [], ['groups' => ['topic_show']]);
    }

    /**
     * @Route("/{id}/archive", name="topic-archive")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function archiveAction(Request $request)
    {
        $service = $this->getTopicService();
        $topic = $service->getTopic($request->get('id'), $allowArchived = true);

        $this->get('serializer')->deserialize(
            $request->getContent(),
            Topic::class,
            'json',
            ['object_to_populate' => $topic]
        );

        $topic->setLectureHeld(true);
        $service->update($topic);
        $service->markAsHeld($topic);

        return $this->json($topic, 200, [], ['groups' => ['topic_show']]);
    }


    /**
     * @Route("/{id}/cast-vote", name="topic-cast-vote")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function castVoteAction(Request $request)
    {
        $service = $this->getTopicService();
        $topic = $service->getTopic($request->get('id'));

        $service->addVote($topic, $this->getUser());

        return $this->json('success');
    }

    /**
     * @Route("/{id}/retract-vote", name="topic-retract-vote")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function retractVoteAction(Request $request)
    {
        $service = $this->getTopicService();
        $topic = $service->getTopic($request->get('id'));

        $service->removeVote($topic, $this->getUser());

        return $this->json('success');
    }

    /**
     * @Route("/{id}/comment", name="topic-comment")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function commentAction(Request $request)
    {
        $service = $this->getTopicService();
        $topic = $service->getTopic($request->get('id'));
        $user = $this->getUser();

        $body = json_decode($request->getContent(), true);

        $service->comment($topic, $user, $body['comment']);

        return $this->json('success');
    }

    /**
     * @Route("/{id}/add-speaker", name="topic-add-speaker")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addSpeakerAction(Request $request)
    {
        $service = $this->getTopicService();
        $topic = $service->getTopic($request->get('id'));

        $service->addSpeaker($topic, $this->getUser());

        return $this->json('success');
    }

    /**
     * @Route("/{id}/remove-speaker", name="topic-remove-speaker")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function removeSpeakerAction(Request $request)
    {
        $service = $this->getTopicService();
        $topic = $service->getTopic($request->get('id'));

        $service->removeSpeaker($topic, $this->getUser());

        return $this->json('success');
    }

    /**
     * @return \AppBundle\Service\TopicService
     */
    protected function getTopicService()
    {
        return $this->get('talkly.service.topic');
    }
}
