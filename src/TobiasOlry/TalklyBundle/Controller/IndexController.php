<?php

namespace TobiasOlry\TalklyBundle\Controller;

use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     *
     * @return array
     */
    public function dashboardAction()
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

        $json = $this->get('jms_serializer')->serialize(
            $topics,
            'json',
            SerializationContext::create()->enableMaxDepthChecks()
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

        $json = $this->get('jms_serializer')->serialize(
            $topics,
            'json',
            SerializationContext::create()->enableMaxDepthChecks()
        );

        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @return \TobiasOlry\TalklyBundle\Repository\TopicRepository
     */
    protected function getTopicRepository()
    {
        return $this->get('talkly.repository.topic');
    }
}
