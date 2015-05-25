<?php
/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\TalklyBundle\Event\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use TobiasOlry\TalklyBundle\Entity\Topic;
use TobiasOlry\TalklyBundle\Entity\User;
use TobiasOlry\TalklyBundle\Event\CommentEvent;
use TobiasOlry\TalklyBundle\Event\Events;
use TobiasOlry\TalklyBundle\Event\NotificationMessage;
use TobiasOlry\TalklyBundle\Event\NotificationTransport\TransportInterface;
use TobiasOlry\TalklyBundle\Event\TopicEvent;
use TobiasOlry\TalklyBundle\Service\TopicService;
use TobiasOlry\TalklyBundle\Service\UserService;

class NotificationSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var TopicService
     */
    private $topicService;

    /**
     * @var TokenStorageInterface
     */
    private $security;

    /**
     * @var array|TransportInterface[]
     */
    private $transports = [];

    public function __construct(
        UserService $userService,
        TopicService $topicService,
        TokenStorageInterface $security
    ) {
        $this->userService  = $userService;
        $this->topicService = $topicService;
        $this->security     = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::TOPIC_CREATED          => 'onTopicCreated',
            Events::TOPIC_UPDATED          => 'onTopicUpdated',
            Events::COMMENT_CREATED        => 'onCommentCreated',
            Events::TOPIC_TALK_SCHEDULED   => 'onTalkScheduled',
            Events::TOPIC_TALK_UNSCHEDULED => 'onTalkUnscheduled',
            Events::TOPIC_SPEAKER_FOUND    => 'onSpeakerFound',
            Events::TOPIC_TALK_HELD        => 'onTalkHeld',
        ];
    }

    public function addTransport(TransportInterface $transport)
    {
        $this->transports[] = $transport;
    }

    public function onTopicCreated(TopicEvent $event)
    {
        $message = NotificationMessage::create(
            sprintf("New Topic #%d was created by %s", $event->getTopic()->getId(),
                $this->security->getToken()->getUser()),
            $event->getTopic()->getDescription()
        );

        $this->publishToEveryone($message);
    }

    public function onTopicUpdated(TopicEvent $event)
    {
        $message = NotificationMessage::create(
            sprintf("Information on Topic #%d has been updated by %s",
                $event->getTopic()->getId(),
                $this->security->getToken()->getUser()
            ),
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
            "new speaker: " . $this->security->getToken()->getUser()
        );

        $this->publishToTopicSubscribers($event->getTopic(), $message);
    }

    public function onTalkScheduled(TopicEvent $event)
    {
        $message = NotificationMessage::create(
            sprintf("Topic #%d got scheduled for %s.", $event->getTopic()->getId(),
                $event->getTopic()->getLectureDate()->format('Y-m-d')),
            "Talk was scheduled by " . $this->security->getToken()->getUser()
        );

        $this->publishToTopicSubscribers($event->getTopic(), $message);
    }

    public function onTalkUnscheduled(TopicEvent $event)
    {
        $message = NotificationMessage::create(
            sprintf("Topic #%d got unscheduled.", $event->getTopic()->getId()),
            "Talk was unscheduled by " . $this->security->getToken()->getUser()
        );

        $this->publishToTopicSubscribers($event->getTopic(), $message);
    }

    public function onTalkHeld(TopicEvent $event)
    {
        $message = NotificationMessage::create(
            sprintf("Topic #%d was archived by %s.", $event->getTopic()->getId(),
                $this->security->getToken()->getUser()),
            $event->getTopic()->getLectureNote()
        );

        $this->publishToTopicSubscribers($event->getTopic(), $message);
    }

    private function publishToTopicSubscribers(Topic $topic, NotificationMessage $message)
    {
        foreach ($this->topicService->findAllParticipants($topic) as $user) {
            if ($this->security->getToken()->getUser() == $user) {
                continue;
            }
            $this->publish($user, $message);
        }
    }

    private function publishToEveryone(NotificationMessage $message)
    {
        foreach ($this->userService->findAll() as $user) {
            if ($this->security->getToken()->getUser() == $user) {
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
