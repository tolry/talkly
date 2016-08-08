<?php

namespace TobiasOlry\TalklyBundle\Security\Authenticator;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class NtlmAuthenticator extends AbstractAuthenticator
{
    /**
     * @var string
     */
    private $domain;

    /**
     * @param JWTManager $manager
     * @param string $domain
     */
    public function __construct(JWTManager $manager, $domain)
    {
        parent::__construct($manager);

        $this->domain = $domain;
    }

    /**
     * @param Request $request
     * @return array|null
     */
    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() !== '/api/login') {
            return null;
        }

        list($domain, $username) = explode('\\', $request->server->get('REMOTE_USER'));

        return [
            'username' => $username,
            'domain' => $domain
        ];
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!$credentials['username']) {
            return false;
        }

        if ($credentials['domain'] !== $this->domain) {
            return false;
        }

        return true;
    }
}