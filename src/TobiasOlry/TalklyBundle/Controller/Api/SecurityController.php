<?php

namespace TobiasOlry\TalklyBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use TobiasOlry\TalklyBundle\Entity\User;

class SecurityController extends Controller
{
    /**
     * @Route("/api/security/user/", name="api_security_user")
     *
     * @return JsonResponse
     */
    public function userAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        return new JsonResponse(['id' => $user->getId(), 'name' => $user->getUsername()]);
    }
}