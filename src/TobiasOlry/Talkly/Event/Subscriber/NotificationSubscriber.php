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
use TobiasOlry\Talkly\Event\NotificationMessage;
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
            Events::TOPIC_UPDATED        => 'onTopicUpdated',
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
        $message = NotificationMessage::create(
            sprintf("New Topic #%d was created by %s", $event->getTopic()->getId(), $this->security->getUser()),
            $event->getTopic()->getDescription()
        );

        $this->publishToEveryone($message);
    }

    public function onTopicUpdated(TopicEvent $event)
    {
        $message = NotificationMessage::create(
            sprintf("Information on Topic #%d has been updated by %s", $event->getTopic()->getId(), $this->security->getUser()),
            $event->getTopic()->getDescription()
        );

        $this->publishToTopicSubscribers($event->getTopic(), $message);
    }

    public function onCommentCreated(CommentEvent $event)
    {
        $topic   = $event->getComment()->getTopic();
        $message = NotificationMessage::create(
            sprintf("New Comment on Topic #%d by %s", $topic->getId(), $event->getComment()->getCreatedBy()),
            $event->getComment()->getCommentText()
        );

        $this->publishToTopicSubscribers($topic, $message);
    }

    public function onSpeakerFound(TopicEvent $event)
    {
        $message = NotificationMessage::create(
            sprintf("We have a speaker for topic #%d.", $event->getTopic()->getId()),
            "new speaker: " . $this->security->getUser()
        );

        $this->publishToTopicSubscribers($event->getTopic(), $message);
    }

    public function onTalkScheduled(TopicEvent $event)
    {
        $message = NotificationMessage::create(
            sprintf("Topic #%d got scheduled for %s.", $event->getTopic()->getId(), $event->getTopic()->getLectureDate()->format('Y-m-d')),
            "Talk was scheduled by " . $this->security->getUser()
        );

        $this->publishToTopicSubscribers($event->getTopic(), $message);
    }

    public function onTalkHeld(TopicEvent $event)
    {
        $message = NotificationMessage::create(
            sprintf("Topic #%d was archivedi by %s.", $event->getTopic()->getId(), $this->security->getUser()),
            $event->getTopic()->getLectureNote()
        );

        $this->publishToTopicSubscribers($event->getTopic(), $message);
    }

    private function publishToTopicSubscribers(Topic $topic, NotificationMessage $message)
    {
        foreach ($this->topicService->findAllParticipants($topic) as $user) {
            if ($this->security->getUser() == $user) {
                continue;
            }
            $this->publish($user, $message);
        }
    }

    private function publishToEveryone(NotificationMessage $message)
    {
        foreach ($this->userService->findAll() as $user) {
            if ($this->security->getUser() == $user) {
                continue;
            }
            $this->publish($user, $message);
        }
    }

    private function publish(User $user, NotificationMessage $message)
    {
        foreach ($this->transports as $transport) {
            $transport->addNotification($user, $message);
        }
    }
}
