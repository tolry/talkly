<?php

namespace AppBundle\Notification;

/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */
class Message
{
    /**
     * @var string
     */
    public $subject;

    /**
     * @var string
     */
    public $details;

    /**
     * @param string $subject markdown string
     * @param string $details markdown string
     *
     * @return self
     */
    public static function create($subject, $details)
    {
        $message          = new self();
        $message->subject = $subject;
        $message->details = $details;

        return $message;
    }
}

