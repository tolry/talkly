<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Notification;
use AppBundle\Entity\User;

class UserService
{
    private $em;
    private $repository;

    public function __construct(EntityManager $em)
    {
        $this->em         = $em;
        $this->repository = $em->getRepository('AppBundle\Entity\User');
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

