<?php

namespace TobiasOlry\TalklyBundle\Security\Authentication;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class NtlmToken extends AbstractToken
{
    public function __construct(array $roles = array())
    {
        parent::__construct($roles);

        $this->setAuthenticated(count($roles) >= 1);
    }

    public function getCredentials()
    {
        return '';
    }
}