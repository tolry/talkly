<?php

namespace TobiasOlry\TalklyBundle\Security\Authentication;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class NtlmUserProvider implements AuthenticationProviderInterface
{
    private $userProvider;

    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof NtlmToken;
    }

    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());

        if (! $user) {
            throw new AuthenticationException('The NTLM authentication failed.');
        }

        $authenticatedToken = new NtlmToken($user->getRoles());
        $authenticatedToken->setUser($user);

        return $authenticatedToken;
    }
}
