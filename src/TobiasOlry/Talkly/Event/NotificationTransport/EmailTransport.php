<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Event\NotificationTransport;

use \Swift_Mailer;
use TobiasOlry\Talkly\Entity\User;

class EmailTransport implements TransportInterface
{
    protected $mailer;

    public function __construct(
        Swift_Mailer $mailer
    )
    {
        $this->mailer       = $mailer;
    }

    public function addNotification(User $user, $message)
    {
        if (! $user->getNotifyByEmail()) {

            return;
        }

        if (! $user->getEmail()) {

            return;
        }

        $message = \Swift_Message::newInstance()
            ->setSubject('new notification')
            ->setBody($message)
            ->setFrom(array('tobias.olry@gmail.com'))
            ->setTo(array('tobias.olry@gmail.com'))
        ;

        $this->mailer->send($message);
    }
}

