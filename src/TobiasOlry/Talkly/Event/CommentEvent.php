<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Event;

use TobiasOlry\Talkly\Entity\Comment;
use TobiasOlry\Talkly\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class CommentEvent extends Event
{
    private $comment;
    private $actingUser;

    public function __construct(Comment $comment, User $actingUser)
    {
        $this->comment    = $comment;
        $this->actingUser = $actingUser;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getActingUser()
    {
        return $this->actingUser;
    }
}
