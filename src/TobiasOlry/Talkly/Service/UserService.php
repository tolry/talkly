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

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function save(User $user)
    {
        $this->em->flush($user);
    }
}

