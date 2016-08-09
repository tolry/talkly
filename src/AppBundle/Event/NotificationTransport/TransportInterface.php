<?php
/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace AppBundle\Event\NotificationTransport;

use AppBundle\Entity\User;
use AppBundle\Event\NotificationMessage;

interface TransportInterface
{
    public function addNotification(User $user, NotificationMessage $message);
}

