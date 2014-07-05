<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Event\NotificationTransport;

use TobiasOlry\Talkly\Entity\User;
use TobiasOlry\Talkly\Service\UserService;

class DatabaseTransport implements TransportInterface
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function addNotification(User $user, $message)
    {
        $this->userService->addNotification($user, $message);
    }
}

