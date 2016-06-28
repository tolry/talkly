<?php

namespace TobiasOlry\TalklyBundle\Security\Authenticator;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author David Badura <david.badura@i22.de>
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