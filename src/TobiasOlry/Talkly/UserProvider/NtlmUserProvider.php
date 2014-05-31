<?php
/*
 * @author Tobias Olry <olry@simplethings.de>
 */

namespace TobiasOlry\Talkly\UserProvider;

use Symfony\Component\HttpFoundation\RequestStack;

class NtlmUserProvider implements UserProviderInterface
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getUsername()
    {
        $username = $this->requestStack->getCurrentRequest()->server->get('REMOTE_USER');

        if (empty($username)) {
            return;
        }

        return $username;
    }
}

