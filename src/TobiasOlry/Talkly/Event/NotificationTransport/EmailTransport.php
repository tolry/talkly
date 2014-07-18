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
    private $twig;

    public function __construct(
        Swift_Mailer $mailer,
        \Twig_Environment $twig,
        $emailSender
    )
    {
        $this->mailer      = $mailer;
        $this->twig        = $twig;
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

        $html = $this->twig->render(
            'mail/notification.html.twig',
            [
                'user'    => $user,
                'message' => $message,
            ]
        );

        $message = \Swift_Message::newInstance()
            ->setSubject('new notification')
            ->setBody($html, 'text/html')
            ->setFrom([$this->emailSender => 'Talkly Mailbot'])
            ->setTo([$user->getEmail() => (string) $user])
        ;

        $this->mailer->send($message);
    }
}

