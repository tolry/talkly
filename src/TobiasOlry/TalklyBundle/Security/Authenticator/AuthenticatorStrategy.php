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
 * @author David Badura <d.a.badura@gmail.com>
 */
class AuthenticatorStrategy extends AbstractGuardAuthenticator
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var AbstractGuardAuthenticator[]
     */
    private $authenticators;

    /**
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @param string $type
     * @param AbstractGuardAuthenticator $authenticator
     */
    public function addAuthenticator($type, AbstractGuardAuthenticator $authenticator)
    {
        $this->authenticators[$type] = $authenticator;
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return null|Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return $this->getAuthenticator()->start($request, $authException);
    }

    /**
     * @param Request $request
     * @return array|null
     */
    public function getCredentials(Request $request)
    {
        return $this->getAuthenticator()->getCredentials($request);
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
        return $this->getAuthenticator()->checkCredentials($credentials, $user);
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return null|Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return $this->getAuthenticator()->onAuthenticationFailure($request, $exception);
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return null|Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return $this->getAuthenticator()->onAuthenticationSuccess($request, $token, $providerKey);
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        return $this->getAuthenticator()->supportsRememberMe();
    }

    /**
     * @return AbstractGuardAuthenticator
     * @throws \RuntimeException
     */
    private function getAuthenticator()
    {
        if (!isset($this->authenticators[$this->type])) {
            throw new \RuntimeException(sprintf('"%s" not supported', $this->type));
        }

        return $this->authenticators[$this->type];
    }
}