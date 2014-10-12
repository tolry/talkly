<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Event\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use TobiasOlry\Talkly\Service\TopicService;
use TobiasOlry\Talkly\Service\UserService;
use TobiasOlry\Talkly\Security\Security;

use TobiasOlry\Talkly\Event\TopicEvent;
use TobiasOlry\Talkly\Event\CommentEvent;
use TobiasOlry\Talkly\Event\Events;
use TobiasOlry\Talkly\Event\NotificationTransport\TransportInterface;

use TobiasOlry\Talkly\Entity\User;
use TobiasOlry\Talkly\Entity\Topic;

class NotificationSubscriber implements EventSubscriberInterface
{
    private $userService;
    private $topicService;
    private $security;

    private $transports = [];

    public function __construct(
        UserService $userService,
        TopicService $topicService,
        Security $security
    ) {
        $this->userService  = $userService;
        $this->topicService = $topicService;
        $this->security     = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::TOPIC_CREATED        => 'onTopicCreated',
            Events::COMMENT_CREATED      => 'onCommentCreated',
            Events::TOPIC_TALK_SCHEDULED => 'onTalkScheduled',
            Events::TOPIC_SPEAKER_FOUND  => 'onSpeakerFound',
            Events::TOPIC_TALK_HELD      => 'onTalkHeld',
        ];
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

        $this->publishToEveryone($message);
    }

    public function onTopicUpdated(TopicEvent $event)
    {
        $message = sprintf(
            "Information on Topic #%d have been updated",
            $event->getTopic()->getId(),
            $event->getTopic()->getTitle()
        );

        $this->publishToTopicSubscribers($topic, $message);
    }

    public function onCommentCreated(CommentEvent $event)
    {
        $topic   = $event->getComment()->getTopic();
        $message = sprintf(
            "New Comment on Topic #%d by %s",
            $topic->getId(),
            $event->getComment()->getCreatedBy()
        );

        $this->publishToTopicSubscribers($topic, $message);
    }

    public function onSpeakerFound(TopicEvent $event)
    {
        $message = sprintf(
            "We have a speaker for Topic #%d.",
            $event->getTopic()->getId()
        );

        $this->publishToTopicSubscribers($event->getTopic(), $message);
    }

    public function onTalkScheduled(TopicEvent $event)
    {
        $message = sprintf(
            "Topic #%d got scheduled for %s.",
            $event->getTopic()->getId(),
            $event->getTopic()->getLectureDate()->format('Y-m-d')
        );

        $this->publishToTopicSubscribers($event->getTopic(), $message);
    }

    public function onTalkHeld(TopicEvent $event)
    {
        $message = sprintf(
            "Topic #%d was archived.",
            $event->getTopic()->getId()
        );

        $this->publishToTopicSubscribers($event->getTopic(), $message);
    }

    private function publishToTopicSubscribers(Topic $topic, $message)
    {
        foreach ($this->topicService->findAllParticipants($topic) as $user) {
            if ($this->security->getUser() == $user) {
                continue;
            }
            $this->publish($user, $message);
        }
    }

    private function publishToEveryone($message)
    {
        foreach ($this->userService->findAll() as $user) {
            if ($this->security->getUser() == $user) {
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
