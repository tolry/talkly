<?php
/*
 * (c) SimpleThings GmbH
 *
 * @author Tobias Olry <tobias.olry@web.de>
 */

namespace TobiasOlry\Talkly\UserProvider;

class DebugUserProvider implements UserProviderInterface
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function getUsername()
    {
        return $this->user;
    }
}

