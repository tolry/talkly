<?php
/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\TalklyBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use TobiasOlry\TalklyBundle\Entity\Topic;

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
