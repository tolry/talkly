<?php
/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\TalklyBundle\Event\NotificationTransport;

use TobiasOlry\TalklyBundle\Entity\User;
use TobiasOlry\TalklyBundle\Event\NotificationMessage;
use TobiasOlry\TalklyBundle\Service\UserService;

class DatabaseTransport implements TransportInterface
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function addNotification(User $user, NotificationMessage $message)
    {
        if (! $user->getNotifyInApplication()) {

            return;
        }

        $this->userService->addNotification($user, $message->subject);
    }
}
