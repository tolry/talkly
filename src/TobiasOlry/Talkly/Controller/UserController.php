<?php
namespace TobiasOlry\Talkly\Controller;

use Symfony\Component\HttpFoundation\Request;
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

    public function __construct(
        $formFactory,
        $urlGenerator,
        \Twig_Environment $twig,
        $security
    ) {
        $this->formFactory  = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->twig         = $twig;
        $this->security     = $security;
    }

    public function userProfileAction(Request $request)
    {
        $form = $this->formFactory->create(
            new UserProfileType(),
            $this->security->getUser()
        );
    }
}

