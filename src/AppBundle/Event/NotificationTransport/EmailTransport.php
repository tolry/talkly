<?php
/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace AppBundle\Event\NotificationTransport;

use AppBundle\Entity\User;
use AppBundle\Event\NotificationMessage;

class EmailTransport implements TransportInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $emailSender;

    /**
     * @var \Twig_Environment
     */
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
