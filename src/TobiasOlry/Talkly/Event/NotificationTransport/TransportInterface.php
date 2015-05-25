<?php
/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Event\NotificationTransport;

use TobiasOlry\Talkly\Event\NotificationMessage;
use TobiasOlry\TalklyBundle\Entity\User;

interface TransportInterface
{
    public function addNotification(User $user, NotificationMessage $message);
}

