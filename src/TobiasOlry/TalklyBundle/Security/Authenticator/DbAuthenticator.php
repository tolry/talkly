<?php

namespace TobiasOlry\TalklyBundle\Security\Authenticator;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class DbAuthenticator extends AbstractAuthenticator
{
    /**
     * @var UserPasswordEncoder
     */
    private $passwordEncoder;

    /**
     * @param JWTManager $manager
     * @param UserPasswordEncoder $passwordEncoder
     */
    public function __construct(JWTManager $manager, UserPasswordEncoder $passwordEncoder)
    {
        parent::__construct($manager);

        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }
}