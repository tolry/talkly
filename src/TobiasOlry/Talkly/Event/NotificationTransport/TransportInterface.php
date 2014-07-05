<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Event\NotificationTransport;

use TobiasOlry\Talkly\Entity\User;

interface TransportInterface
{
    function addNotification(User $user, $message);
}

