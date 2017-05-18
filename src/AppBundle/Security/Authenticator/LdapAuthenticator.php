<?php

namespace AppBundle\Security\Authenticator;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\Ldap\Exception\ConnectionException;
use Symfony\Component\Ldap\LdapClient;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class LdapAuthenticator extends AbstractLoginAuthenticator
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $dnString;

    /**
     * @param JWTManager $manager
     * @param string $url
     * @param string $dnString
     */
    public function __construct(JWTManager $manager, $url, $dnString)
    {
        parent::__construct($manager);

        $this->url = $url;
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

        $ldap = new LdapClient($this->url);

        try {
            $username = $ldap->escape($username, '', LDAP_ESCAPE_DN);
            $dn = str_replace('{username}', $username, $this->dnString);

            $ldap->bind($dn, $password);
        } catch (ConnectionException $e) {
            return false;
        }

        return true;
    }
}
