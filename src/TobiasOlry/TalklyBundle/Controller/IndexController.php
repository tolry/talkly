<?php

namespace TobiasOlry\TalklyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TobiasOlry\TalklyBundle\Entity\Topic;
use TobiasOlry\TalklyBundle\Form\CreateTopicType;

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
     * @return array
     */
    public function archiveAction()
    {
        $topics = $this->getTopicRepository()->findArchivedGroupByMonth();

        return ['topics' => $topics];
    }

    /**
     * @Route("/calendar/", name="calendar")
     * @Template()
     *
     * @return array
     */
    public function calendarAction()
    {
        $topics = $this->getTopicRepository()->findNextGroupByMonth();

        return ['topics' => $topics];
    }

    /**
     * @return \TobiasOlry\TalklyBundle\Repository\TopicRepository
     */
    protected function getTopicRepository()
    {
        return $this->get('talkly.repository.topic');
    }
}
