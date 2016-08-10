<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;
use AppBundle\Service\UserService;

/**
 * @author Tobias Olry <olry@gmail.com>
 */
class UserController extends Controller
{
    /**
     * @Route("/user/current")
     *
     * @return Response
     */
    public function currentAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json($user, 200, [], ['groups' => ['user_self']]);
    }

    /**
     * @Route("/user/{id}", name="user-profile")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function profileAction(Request $request)
    {
        $user = $this->get('doctrine')->getRepository(User::class)->find($request->get('id'));

        $group = $user === $this->getUser() ? 'user_self' : 'user_show';

        return $this->json($user, 200, [], ['groups' => [$group]]);
    }

    /**
     * @Route("/user/{id}/edit")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function editProfileAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();

        $user = $em->getRepository(User::class)->find($request->get('id'));

        $this->get('serializer')->deserialize(
            $request->getContent(),
            User::class,
            'json',
            ['object_to_populate' => $user]
        );

        $em->flush();

        return $this->json($user, 200, [], ['groups' => ['user_show']]);
    }

    /**
     * @return UserService
     */
    protected function getUserService()
    {
        return $this->get('talkly.service.user');
    }
}

