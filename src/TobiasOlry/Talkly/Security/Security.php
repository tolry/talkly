<?php

namespace TobiasOlry\Talkly\Security;

/**
 *
 * @author David Badura <d.a.badura@gmail.com>
 */
class Security
{
    /**
     *
     * @var UserInterface
     */
    protected $user;

    /**
     *
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user = null)
    {
        $this->user = $user;
    }

    /**
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }
}
