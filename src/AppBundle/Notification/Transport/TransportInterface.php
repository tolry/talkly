<?php

namespace AppBundle\Notification\Transport;

use AppBundle\Entity\User;
use AppBundle\Notification\Message;

/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */
interface TransportInterface
{
    /**
     * @param User $user
     * @param Message $message
     */
    public function addNotification(User $user, Message $message);
}

