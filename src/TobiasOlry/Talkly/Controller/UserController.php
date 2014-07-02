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
    private $em;

    public function __construct(
        $formFactory,
        $urlGenerator,
        \Twig_Environment $twig,
        $security,
        $em
    ) {
        $this->formFactory  = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->twig         = $twig;
        $this->security     = $security;
        $this->em           = $em;
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
            $this->em->flush();
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

