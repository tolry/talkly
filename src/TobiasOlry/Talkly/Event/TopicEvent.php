<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Event;

use TobiasOlry\Talkly\Entity\Topic;
use Symfony\Component\EventDispatcher\Event;

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

