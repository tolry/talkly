<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Service;

use Doctrine\ORM\EntityManager;
use TobiasOlry\Talkly\Entity\User;
use TobiasOlry\Talkly\Entity\Notification;

class UserService
{
    private $em;
    private $userRepository;

    public function __construct(EntityManager $em)
    {
        $this->em         = $em;
        $this->repository = $em->getRepository('TobiasOlry\Talkly\Entity\User');
    }

    public function update(User $user)
    {
        $this->em->flush();
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function addNotification(User $user, $message)
    {
        $user->getNotifications()->add(new Notification($user, $message));
        $this->em->flush();
    }
}

