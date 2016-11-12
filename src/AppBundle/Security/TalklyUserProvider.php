<?php

namespace AppBundle\Security;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author Tobias Olry <tobias.olry@gmail.com>
 */
class TalklyUserProvider implements UserProviderInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var string
     */
    private $type;

    /**
     * @param UserRepository $userRepository
     * @param string $type
     */
    public function __construct(UserRepository $userRepository, $type = 'db')
    {
        $this->userRepository = $userRepository;
        $this->type = $type;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function loadUserByUsername($username)
    {
        if ($this->type === 'db') {
            return $this->userRepository->findOneBy([
                'username' => $username
            ]);
        }

        return $this->userRepository->findOrCreate($username);
    }

    /**
     * @param UserInterface $user
     *
     * @return User
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
        return $class === User::class;
    }
}
