<?php
/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\TalklyBundle\Event\NotificationTransport;

use TobiasOlry\TalklyBundle\Entity\User;
use TobiasOlry\TalklyBundle\Event\NotificationMessage;

class EmailTransport implements TransportInterface
{
    private $mailer;
    private $emailSender;
    private $twig;

    public function __construct(
        \Swift_Mailer $mailer,
        \Twig_Environment $twig,
        $emailSender
    ) {
        $this->mailer      = $mailer;
        $this->twig        = $twig;
        $this->emailSender = $emailSender;
    }

    public function addNotification(User $user, NotificationMessage $message)
    {
        if (! $user->getNotifyByEmail()) {
            return;
        }

        if (! $user->getEmail()) {
            return;
        }

        $html = $this->twig->render(
            'mail/notification.html.twig',
            ['user' => $user, 'message' => $message]
        );

        $message = \Swift_Message::newInstance()
            ->setFrom([$this->emailSender => 'Talkly Mailbot'])
            ->setTo([$user->getEmail() => (string) $user])
            ->setSubject('[talkly] ' . $message->subject)
            ->setBody($html, 'text/html');

        $this->mailer->send($message);
    }
}
