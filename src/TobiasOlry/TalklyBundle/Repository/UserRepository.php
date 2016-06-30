<?php

namespace TobiasOlry\TalklyBundle\Repository;

use Doctrine\ORM\EntityRepository;
use TobiasOlry\TalklyBundle\Entity\User;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class UserRepository extends EntityRepository
{
    /**
     * @param string $username
     *
     * @return User
     */
    public function findOrCreate($username)
    {
        if ($user = $this->findOneBy(['username' => $username])) {
            return $user;
        }

        $user = new User($username);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush($user);

        return $user;
    }
}