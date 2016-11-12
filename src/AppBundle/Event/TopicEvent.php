<?php

namespace AppBundle\Event;

use AppBundle\Entity\Topic;
use Symfony\Component\EventDispatcher\Event;

/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */
class TopicEvent extends Event
{
    private $topic;

    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
    }

    public function getTopic()
    {
        return $this->topic;
    }
}
