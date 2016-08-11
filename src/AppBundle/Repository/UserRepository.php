<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Notification;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;

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
        /** @var User $user */
        if ($user = $this->findOneBy(['username' => $username])) {
            return $user;
        }

        $user = new User($username);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush($user);

        return $user;
    }

    /**
     * @param User $user
     * @param $message
     */
    public function addNotification(User $user, $message)
    {
        $user->getNotifications()->add(new Notification($user, $message));
        $this->getEntityManager()->flush();
    }

    /**
     * @param User $user
     */
    public function save(User $user)
    {
        $this->getEntityManager()->flush();
    }
}