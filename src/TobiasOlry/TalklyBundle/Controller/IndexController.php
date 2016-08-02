<?php

namespace TobiasOlry\TalklyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     *
     * @return Response
     */
    public function archiveAction()
    {
        $topics = $this->getTopicRepository()->findArchivedGroupByMonth();

        return $this->json($topics, 200, [], ['groups' => ['topic_list']]);
    }

    /**
     * @Route("/calendar/", name="calendar")
     *
     * @return Response
     */
    public function calendarAction()
    {
        $topics = $this->getTopicRepository()->findNextGroupByMonth();

        return $this->json($topics, 200, [], ['groups' => ['topic_list']]);
    }

    /**
     * @Route("/markdown", name="markdown")
     *
     * @param Request $request
     * @return Response
     */
    public function markdownAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $data['html'] = $this->get('talkly.markdown')->convertToHtml($data['markdown']);

        return $this->json($data);
    }

    /**
     * @return \TobiasOlry\TalklyBundle\Repository\TopicRepository
     */
    protected function getTopicRepository()
    {
        return $this->get('talkly.repository.topic');
    }
}
