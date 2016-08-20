<?php

namespace AppBundle\Notification\Transport;

use AppBundle\Entity\User;
use AppBundle\Notification\Message;
use AppBundle\Repository\UserRepository;

/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */
class DatabaseTransport implements TransportInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param User $user
     * @param Message $message
     */
    public function addNotification(User $user, Message $message)
    {
        if (! $user->getNotifyInApplication()) {
            return;
        }

        $this->userRepository->addNotification($user, $message->subject);
    }
}
