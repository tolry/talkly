<?php

namespace AppBundle\Security\Authenticator;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\Ldap\Exception\ConnectionException;
use Symfony\Component\Ldap\LdapClient;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author David Badura <d.a.badura@gmail.com>
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
     * @param JWTManager $manager
     * @param LdapClient $ldap
     * @param string $dnString
     */
    public function __construct(JWTManager $manager, LdapClient $ldap, $dnString)
    {
        parent::__construct($manager);

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