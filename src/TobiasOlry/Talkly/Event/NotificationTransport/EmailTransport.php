<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Event\NotificationTransport;

use \Swift_Mailer;
use TobiasOlry\Talkly\Entity\User;

class EmailTransport implements TransportInterface
{
    private $mailer;
    private $emailSender;

    public function __construct(
        Swift_Mailer $mailer,
        $emailSender
    )
    {
        $this->mailer      = $mailer;
        $this->emailSender = $emailSender;
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
            ->setFrom(array($this->emailSender))
            ->setTo(array($user->getEmail() => (string) $user))
        ;

        $this->mailer->send($message);
    }
}

