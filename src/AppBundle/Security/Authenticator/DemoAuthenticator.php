<?php

namespace AppBundle\Security\Authenticator;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class DemoAuthenticator extends AbstractAuthenticator
{
    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return $credentials['password'] === 'password';
    }
}