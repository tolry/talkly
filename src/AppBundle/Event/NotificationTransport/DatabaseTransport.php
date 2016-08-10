<?php
/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace AppBundle\Event\NotificationTransport;

use AppBundle\Entity\User;
use AppBundle\Event\NotificationMessage;
use AppBundle\Service\UserService;

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
