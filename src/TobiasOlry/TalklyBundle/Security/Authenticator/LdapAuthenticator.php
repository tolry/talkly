<?php

namespace TobiasOlry\TalklyBundle\Security\Authenticator;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Ldap\Exception\ConnectionException;
use Symfony\Component\Ldap\LdapClient;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * @author David Badura <david.badura@i22.de>
 */
class LdapAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var LdapClient
     */
    private $ldap;

    /**
     * @var string
     */
    private $dnString;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param LdapClient $ldap
     * @param string $dnString
     * @param RouterInterface $router
     */
    public function __construct(LdapClient $ldap, $dnString, RouterInterface $router)
    {
        $this->ldap = $ldap;
        $this->dnString = $dnString;
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return null|Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->router->generate('login'));
    }

    /**
     * @param Request $request
     * @return array|null
     */
    public function getCredentials(Request $request)
    {
        if (!$request->request->has('_username') || !$request->request->get('_password')) {
            return null;
        }

        return [
            'username' => $request->request->get('_username'),
            'password' => $request->request->get('_password')
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
        $username = $credentials['username'];
        $password = $credentials['password'];

        try {

            $username = $this->ldap->escape($username, '', LDAP_ESCAPE_DN);
            $dn = str_replace('{username}', $username, $this->dnString);

            $this->ldap->bind($dn, $password);

        } catch (ConnectionException $e) {
            return false;
        }

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
        return new RedirectResponse($this->router->generate('homepage'));
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        return true;
    }
}