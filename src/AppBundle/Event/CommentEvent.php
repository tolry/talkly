<?php

namespace AppBundle\Event;

use AppBundle\Entity\Comment;
use Symfony\Component\EventDispatcher\Event;

/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */
class CommentEvent extends Event
{
    private $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function getComment()
    {
        return $this->comment;
    }
}
