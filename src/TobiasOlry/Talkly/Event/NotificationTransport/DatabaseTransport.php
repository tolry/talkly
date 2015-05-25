<?php
/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Event\NotificationTransport;

use TobiasOlry\Talkly\Event\NotificationMessage;
use TobiasOlry\TalklyBundle\Entity\User;
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
