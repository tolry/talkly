<?php

namespace TobiasOlry\Talkly\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use TobiasOlry\Talkly\Entity\User;
use TobiasOlry\Talkly\Security\Security;

class SecurityController
{
    /** @var Security */
    private $security;

    /**
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @return JsonResponse
     */
    public function userAction()
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return new JsonResponse(['id' => $user->getId(), 'name' => $user->getUsername()]);
    }
}