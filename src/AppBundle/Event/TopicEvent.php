<?php
/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\Topic;

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
