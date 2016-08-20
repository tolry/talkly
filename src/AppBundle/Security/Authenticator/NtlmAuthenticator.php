<?php

namespace AppBundle\Security\Authenticator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class NtlmAuthenticator extends AbstractPreAuthenticator
{
    /**
     * @var string
     */
    private $domain;

    /**
     * @param RouterInterface $router
     * @param string $domain
     */
    public function __construct(RouterInterface $router, $domain)
    {
        parent::__construct($router);

        $this->domain = $domain;
    }

    /**
     * @param Request $request
     * @return array|null
     */
    public function getCredentials(Request $request)
    {
        if (strpos($request->getPathInfo(), '/api/') === 0) {
            return null;
        }

        $parts = explode('\\', $request->server->get('REMOTE_USER'));

        if (count($parts) !== 2) {
            return null;
        }

        return [
            'domain' => $parts[0],
            'username' => $parts[1]
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