<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Event\NotificationTransport;

use TobiasOlry\Talkly\Entity\User;
use TobiasOlry\Talkly\Event\NotificationMessage;

interface TransportInterface
{
    public function addNotification(User $user, NotificationMessage $message);
}

