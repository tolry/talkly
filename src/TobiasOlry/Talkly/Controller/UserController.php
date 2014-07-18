<?php
namespace TobiasOlry\Talkly\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\NotFoundHttpException;

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
            $this->userService->update($user);

            $request->getSession()->getFlashBag()->add('success', 'user-profile updated');
            $url = $this->urlGenerator->generate('user-profile');

            return new RedirectResponse($url);
        }

        return new Response(
            $this->twig->render(
                'user/profile.html.twig',
                [
                    'user' => $user,
                    'form' => $form->createView()
                ]
            )
        );
    }

    public function userNotificationsAction(Request $request)
    {
        $user = $this->security->getUser();

        return new Response(
            $this->twig->render(
                'user/notifications.html.twig',
                ['user' => $user]
            )
        );
    }

    private function getNotification($id)
    {
        $notifications = $this->security->getUser()->getUnreadNotifications();
        foreach ( $notifications as $notification) {
            if ($notification->getId() == $id) {

                return $notification;
            }
        }

        throw new NotFoundHttpException;
    }

    public function markAllNotificationsReadAction()
    {
        $user = $this->security->getUser();
        foreach ($user->getUnreadNotifications() as $notification) {
            $notification->markAsDone();
        }

        $this->userService->update($user);

        $url = $this->urlGenerator->generate('user-notifications');

        return new RedirectResponse($url);
    }

    public function markNotificationReadAction(Request $request)
    {
        $notification = $this->getNotification($request->get('id'));

        $notification->markAsDone();
        $this->userService->update($this->security->getUser());

        $url = $this->urlGenerator->generate('user-notifications');

        return new RedirectResponse($url);

    }
}

