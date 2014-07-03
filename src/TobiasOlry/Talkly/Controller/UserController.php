<?php
namespace TobiasOlry\Talkly\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use TobiasOlry\Talkly\Form\UserProfileType;

/*
 * @author Tobias Olry <olry@gmail.com>
 */
class UserController
{
    private $formFactory;
    private $urlGenerator;
    private $twig;
    private $security;
    private $userService;

    public function __construct(
        $userService,
        $formFactory,
        $urlGenerator,
        \Twig_Environment $twig,
        $security
    ) {
        $this->userService  = $userService;
        $this->formFactory  = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->twig         = $twig;
        $this->security     = $security;
    }

    public function userProfileAction(Request $request)
    {
        $user = $this->security->getUser();
        $form = $this->formFactory->create(
            new UserProfileType(),
            $user
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->userService->save($user);

            $request->getSession()->getFlashBag()->add('success', 'user-profile updated');
            $url = $this->urlGenerator->generate('user-profile');

            return new RedirectResponse($url);
        }

        return new Response(
            $this->twig->render(
                'user/profile.html.twig',
                array(
                    'user' => $user,
                    'form' => $form->createView()
                )
            )
        );
    }
}

