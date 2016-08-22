<?php

namespace AppBundle\Event\Subscriber;

use AppBundle\Event\CommentEvent;
use AppBundle\Event\Events;
use AppBundle\Event\TopicEvent;
use AppBundle\Notification\Message;
use AppBundle\Notification\NotificationManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */
class NotificationSubscriber implements EventSubscriberInterface
{
    /**
     * @var NotificationManager
     */
    private $notificationManager;

    /**
     * @var TokenStorageInterface
     */
    private $security;

    /**
     * @param NotificationManager $notificationManager
     * @param TokenStorageInterface $security
     */
    public function __construct(NotificationManager $notificationManager, TokenStorageInterface $security)
    {
        $this->notificationManager = $notificationManager;
        $this->security = $security;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            Events::TOPIC_CREATED => 'onTopicCreated',
            Events::TOPIC_UPDATED => 'onTopicUpdated',
            Events::COMMENT_CREATED => 'onCommentCreated',
            Events::TOPIC_TALK_SCHEDULED => 'onTalkScheduled',
            Events::TOPIC_TALK_UNSCHEDULED => 'onTalkUnscheduled',
            Events::TOPIC_SPEAKER_FOUND => 'onSpeakerFound',
            Events::TOPIC_TALK_HELD => 'onTalkHeld',
        ];
    }

    /**
     * @param TopicEvent $event
     */
    public function onTopicCreated(TopicEvent $event)
    {
        $message = Message::create(
            sprintf("New Topic #%d was created by %s", $event->getTopic()->getId(),
                $this->security->getToken()->getUser()),
            $event->getTopic()->getDescription()
        );

        $this->notificationManager->publishToEveryone($message);
    }

    /**
     * @param TopicEvent $event
     */
    public function onTopicUpdated(TopicEvent $event)
    {
        $message = Message::create(
            sprintf("Information on Topic #%d has been updated by %s",
                $event->getTopic()->getId(),
                $this->security->getToken()->getUser()
            ),
            $event->getTopic()->getDescription()
        );

        $this->notificationManager->publishToTopicSubscribers($event->getTopic(), $message);
    }

    /**
     * @param CommentEvent $event
     */
    public function onCommentCreated(CommentEvent $event)
    {
        $topic = $event->getComment()->getTopic();
        $message = Message::create(
            sprintf("New Comment on Topic #%d by %s", $topic->getId(), $event->getComment()->getCreatedBy()),
            $event->getComment()->getCommentText()
        );

        $this->notificationManager->publishToTopicSubscribers($topic, $message);
    }

    /**
     * @param TopicEvent $event
     */
    public function onSpeakerFound(TopicEvent $event)
    {
        $message = Message::create(
            sprintf("We have a speaker for topic #%d.", $event->getTopic()->getId()),
            "new speaker: " . $this->security->getToken()->getUser()
        );

        $this->notificationManager->publishToTopicSubscribers($event->getTopic(), $message);
    }

    /**
     * @param TopicEvent $event
     */
    public function onTalkScheduled(TopicEvent $event)
    {
        $message = Message::create(
            sprintf("Topic #%d got scheduled for %s.", $event->getTopic()->getId(),
                $event->getTopic()->getLectureDate()->format('Y-m-d H:i:s')),
            "Talk was scheduled by " . $this->security->getToken()->getUser()
        );

        $this->notificationManager->publishToTopicSubscribers($event->getTopic(), $message);
    }

    /**
     * @param TopicEvent $event
     */
    public function onTalkUnscheduled(TopicEvent $event)
    {
        $message = Message::create(
            sprintf("Topic #%d got unscheduled.", $event->getTopic()->getId()),
            "Talk was unscheduled by " . $this->security->getToken()->getUser()
        );

        $this->notificationManager->publishToTopicSubscribers($event->getTopic(), $message);
    }

    /**
     * @param TopicEvent $event
     */
    public function onTalkHeld(TopicEvent $event)
    {
        $message = Message::create(
            sprintf("Topic #%d was archived by %s.", $event->getTopic()->getId(),
                $this->security->getToken()->getUser()),
            $event->getTopic()->getLectureNote()
        );

        $this->notificationManager->publishToTopicSubscribers($event->getTopic(), $message);
    }
}
