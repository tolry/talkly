<?php

namespace TobiasOlry\TalklyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    /**
     * @return array
     */
    public function indexAction()
    {
        return $this->render('@TobiasOlryTalkly/base.html.twig');
    }

    /**
     * @Route("/archive/", name="archive")
     * @Template()
     *
     * @return Response
     */
    public function archiveAction()
    {
        $topics = $this->getTopicRepository()->findArchivedGroupByMonth();

        $json = $this->get('serializer')->serialize(
            $topics,
            'json',
            ['groups' => ['topic_list']]
        );

        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/calendar/", name="calendar")
     * @Template()
     *
     * @return Response
     */
    public function calendarAction()
    {
        $topics = $this->getTopicRepository()->findNextGroupByMonth();

        $json = $this->get('serializer')->serialize(
            $topics,
            'json',
            ['groups' => ['topic_list']]
        );

        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/markdown", name="markdown")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function markdownAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $data['html'] = $this->get('webuni_commonmark.default_converter')->convertToHtml($data['markdown']);

        return new JsonResponse($data);
    }

    /**
     * @return \TobiasOlry\TalklyBundle\Repository\TopicRepository
     */
    protected function getTopicRepository()
    {
        return $this->get('talkly.repository.topic');
    }
}
