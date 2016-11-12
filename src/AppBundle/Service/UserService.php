<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 *
 * @deprecated use UserRepository
 */
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
        $this->repository->save($user);
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function addNotification(User $user, $message)
    {
        $this->repository->addNotification($user, $message);
    }
}
