<?php
/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\TalklyBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use TobiasOlry\TalklyBundle\Manager\UserManager;

class TalklyUserProvider implements UserProviderInterface
{
    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param string $username
     *
     * @return \TobiasOlry\TalklyBundle\Entity\User
     */
    public function loadUserByUsername($username)
    {
        return $this->userManager->findOrCreate($username);
    }

    /**
     * @param UserInterface $user
     *
     * @return \TobiasOlry\TalklyBundle\Entity\User
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class == 'TobiasOlry\TalklyBundle\Entity\User';
    }
}
