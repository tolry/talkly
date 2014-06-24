<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Security\UserProvider;

use Symfony\Component\HttpFoundation\RequestStack;

class NtlmUserProvider implements UserProviderInterface
{
    private $requestStack;
    private $domain;

    public function __construct(RequestStack $requestStack, $domain)
    {
        $this->requestStack = $requestStack;
        $this->domain       = $domain;
    }

    public function getUsername()
    {
        $username = $this->requestStack->getCurrentRequest()->server->get('REMOTE_USER');

        if (empty($username)) {
            return;
        }

        if (strpos($username, $this->domain . '\\') !== 0) {
            return;
        }

        $username = str_replace($this->domain . '\\', '', $username);

        return $username;
    }
}
