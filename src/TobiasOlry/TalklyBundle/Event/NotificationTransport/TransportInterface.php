<?php
/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\TalklyBundle\Event\NotificationTransport;

use TobiasOlry\TalklyBundle\Entity\User;
use TobiasOlry\TalklyBundle\Event\NotificationMessage;

interface TransportInterface
{
    public function addNotification(User $user, NotificationMessage $message);
}

