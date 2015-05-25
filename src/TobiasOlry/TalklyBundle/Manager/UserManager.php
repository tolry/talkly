<?php

namespace TobiasOlry\TalklyBundle\Manager;

use Doctrine\ORM\EntityManager;
use TobiasOlry\TalklyBundle\Entity\User;

/**
 *
 * @author David Badura <d.a.badura@gmail.com>
 */
class UserManager
{

    /**
     *
     * @var EntityManager;
     */
    protected $em;

    /**
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     *
     * @param string $username
     *
     * @return User
     */
    public function findOrCreate($username)
    {
        $repo = $this->em->getRepository('TobiasOlry\TalklyBundle\Entity\User');

        if ($user = $repo->findOneBy(['username' => $username])) {
            return $user;
        }

        $user = new User($username);
        $this->em->persist($user);
        $this->em->flush($user);

        return $user;
    }
}