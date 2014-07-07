<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Event\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use TobiasOlry\Talkly\Service\TopicService;
use TobiasOlry\Talkly\Service\UserService;

use TobiasOlry\Talkly\Event\TopicEvent;
use TobiasOlry\Talkly\Event\CommentEvent;
use TobiasOlry\Talkly\Event\Events;
use TobiasOlry\Talkly\Event\NotificationTransport\TransportInterface;
use TobiasOlry\Talkly\Entity\User;

class NotificationSubscriber implements EventSubscriberInterface
{
    private $userService;
    private $topicService;

    private $transports = array();

    public function __construct(
        UserService  $userService,
        TopicService $topicService
    )
    {
        $this->userService  = $userService;
        $this->topicService = $topicService;
    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::TOPIC_CREATED   => 'onTopicCreated',
            Events::COMMENT_CREATED => 'onCommentCreated',
        );
    }

    public function addTransport(TransportInterface $transport)
    {
        $this->transports[] = $transport;
    }

    public function onTopicCreated(TopicEvent $event)
    {
        $message = sprintf(
            "New Topic #%d created",
            $event->getTopic()->getId(),
            $event->getTopic()->getTitle()
        );

        foreach ($this->userService->findAll() as $user) {
            if ($event->getTopic()->getCreatedBy() == $user) {

                continue;
            }

            $this->publish($user, $message);
        }
    }

    public function onCommentCreated(CommentEvent $event)
    {
        $topic   = $event->getComment()->getTopic();
        $message = sprintf(
            "New Comment on Topic #%d by %s",
            $topic->getId(),
            $event->getComment()->getCreatedBy()
        );

        foreach ($this->topicService->findAllParticipants($topic) as $user) {
            if ($event->getComment()->getCreatedBy() == $user) {

                continue;
            }

            $this->publish($user, $message);
        }
    }

    private function publish(User $user, $message)
    {
        foreach ($this->transports as $transport) {
            $transport->addNotification($user, $message);
        }
    }
}

