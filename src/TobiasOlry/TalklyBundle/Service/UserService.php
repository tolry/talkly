<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\TalklyBundle\Service;

use Doctrine\ORM\EntityManager;
use TobiasOlry\TalklyBundle\Entity\Notification;
use TobiasOlry\TalklyBundle\Entity\User;

class UserService
{
    private $em;
    private $repository;

    public function __construct(EntityManager $em)
    {
        $this->em         = $em;
        $this->repository = $em->getRepository('TobiasOlry\TalklyBundle\Entity\User');
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

