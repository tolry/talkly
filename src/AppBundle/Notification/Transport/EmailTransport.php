<?php

namespace AppBundle\Notification\Transport;

use AppBundle\Entity\User;
use AppBundle\Notification\Message;

/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */
class EmailTransport implements TransportInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $emailSender;

    /**
     * EmailTransport constructor.
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     * @param $emailSender
     */
    public function __construct(
        \Swift_Mailer $mailer,
        \Twig_Environment $twig,
        $emailSender
    ) {
        $this->mailer      = $mailer;
        $this->twig        = $twig;
        $this->emailSender = $emailSender;
    }

    /**
     * @param User $user
     * @param Message $message
     */
    public function addNotification(User $user, Message $message)
    {
        if (! $user->getNotifyByEmail()) {
            return;
        }

        if (! $user->getEmail()) {
            return;
        }

        $html = $this->twig->render(
            'AppBundle:Mail:notification.html.twig',
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
