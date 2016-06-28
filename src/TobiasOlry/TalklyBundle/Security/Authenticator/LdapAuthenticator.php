<?php

namespace TobiasOlry\TalklyBundle\Security\Authenticator;

use Symfony\Component\Ldap\Exception\ConnectionException;
use Symfony\Component\Ldap\LdapClient;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author David Badura <david.badura@i22.de>
 */
class LdapAuthenticator extends AbstractAuthenticator
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
     * @param RouterInterface $router
     * @param LdapClient $ldap
     * @param string $dnString
     */
    public function __construct(RouterInterface $router, LdapClient $ldap, $dnString)
    {
        parent::__construct($router);

        $this->ldap = $ldap;
        $this->dnString = $dnString;
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
}