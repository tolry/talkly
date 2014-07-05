<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Service;

use Doctrine\ORM\EntityManager;
use TobiasOlry\Talkly\Entity\User;

class UserService
{
    private $em;
    private $userRepository;

    public function __construct(EntityManager $em)
    {
        $this->em         = $em;
        $this->repository = $em->getRepository('TobiasOlry\Talkly\Entity\User');
    }

    public function save(User $user)
    {
        $this->em->flush($user);
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }
}

