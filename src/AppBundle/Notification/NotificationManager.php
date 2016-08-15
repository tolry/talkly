<?php

namespace AppBundle\Notification;

use AppBundle\Entity\Topic;
use AppBundle\Entity\User;
use AppBundle\Notification\Transport\TransportInterface;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\TopicService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class NotificationManager
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var TopicService
     */
    private $topicService;

    /**
     * @var TokenStorageInterface
     */
    private $security;

    /**
     * @var TransportInterface[]
     */
    private $transports = [];

    /**
     * @param UserRepository $userRepository
     * @param TopicService $topicService
     * @param TokenStorageInterface $security
     */
    public function __construct(
        UserRepository $userRepository,
        TopicService $topicService,
        TokenStorageInterface $security
    ) {
        $this->userRepository  = $userRepository;
        $this->topicService = $topicService;
        $this->security     = $security;
    }

    /**
     * @param TransportInterface $transport
     */
    public function addTransport(TransportInterface $transport)
    {
        $this->transports[] = $transport;
    }

    /**
     * @param Topic $topic
     * @param Message $message
     */
    public function publishToTopicSubscribers(Topic $topic, Message $message)
    {
        foreach ($this->topicService->findAllParticipants($topic) as $user) {
            if ($this->security->getToken()->getUser() == $user) {
                continue;
            }

            $this->publish($user, $message);
        }
    }

    /**
     * @param Message $message
     */
    public function publishToEveryone(Message $message)
    {
        foreach ($this->userRepository->findAll() as $user) {
            if ($this->security->getToken()->getUser() == $user) {
                continue;
            }

            $this->publish($user, $message);
        }
    }

    /**
     * @param User $user
     * @param Message $message
     */
    private function publish(User $user, Message $message)
    {
        foreach ($this->transports as $transport) {
            $transport->addNotification($user, $message);
        }
    }
}