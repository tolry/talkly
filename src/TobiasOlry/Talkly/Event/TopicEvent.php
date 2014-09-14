<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Event;

use TobiasOlry\Talkly\Entity\Topic;
use TobiasOlry\Talkly\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class TopicEvent extends Event
{
    private $topic;
    private $actingUser;

    public function __construct(Topic $topic, User $actingUser)
    {
        $this->topic = $topic;
        $this->actingUser = $actingUser;
    }

    public function getTopic()
    {
        return $this->topic;
    }

    public function getActingUser()
    {
        return $this->actingUser;
    }
}
