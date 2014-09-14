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

    public function __construct(Comment $comment)
    {
        $this->comment    = $comment;
    }

    public function getComment()
    {
        return $this->comment;
    }
}
