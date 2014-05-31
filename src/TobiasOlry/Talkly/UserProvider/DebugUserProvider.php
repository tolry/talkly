<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
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

