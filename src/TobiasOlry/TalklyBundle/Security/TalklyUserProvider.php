<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\TalklyBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use TobiasOlry\TalklyBundle\Entity\User;

class TalklyUserProvider implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        return new User($username);
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class == 'TobiasOlry\TalklyBundle\Entity\User';
    }
}
