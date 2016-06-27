<?php

namespace TobiasOlry\TalklyBundle\Security\Authenticator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * @author David Badura <david.badura@i22.de>
 */
class DebugAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var string
     */
    private $username;

    /**
     * @param $username
     */
    public function __construct($username)
    {
        $this->username = $username;
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return null|Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return null;
    }

    /**
     * @param Request $request
     * @return array|null
     */
    public function getCredentials(Request $request)
    {
        return [
            'username' => $this->username
        ];
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials['username']);
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return null;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }
}