<?php
namespace TobiasOlry\TalklyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TobiasOlry\TalklyBundle\Entity\Notification;
use TobiasOlry\TalklyBundle\Entity\User;
use TobiasOlry\TalklyBundle\Form\UserProfileType;
use TobiasOlry\TalklyBundle\Service\UserService;

/**
 * @author Tobias Olry <olry@gmail.com>
 */
class UserController extends Controller
{
    /**
     * @Route("/user/current")
     *
     * @return JsonResponse
     */
    public function currentAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        return new JsonResponse(['id' => $user->getId(), 'name' => $user->getUsername()]);
    }

    /**
     * @Route("/user/profile", name="user-profile")
     * @Template()
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function profileAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(new UserProfileType(), $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getUserService()->update($user);
            $this->addFlash('success', 'user-profile updated');

            return $this->redirectToRoute('user-profile');
        }

        return ['user' => $user, 'form' => $form->createView()];
    }

    /**
     * @Route("/user/notifications", name="user-notifications")
     * @Template()
     *
     * @return array
     */
    public function notificationsAction()
    {
        return ['user' => $this->getUser()];
    }

    /**
     * @Route("/user/mark-all-notifications-read", name="user-notification-read-all")
     *
     * @return RedirectResponse
     */
    public function markAllNotificationsReadAction()
    {
        $user = $this->getUser();
        foreach ($user->getUnreadNotifications() as $notification) {
            $notification->markAsDone();
        }

        $this->getUserService()->update($user);

        return $this->redirectToRoute('user-notifications');
    }

    /**
     * @Route("/user/notification/{id}/mark-read", name="user-notification-read")
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function markNotificationReadAction(Request $request)
    {
        $notification = $this->getNotification($request->get('id'));
        $notification->markAsDone();

        $this->getUserService()->update($this->getUser());

        return $this->redirectToRoute('user-notifications');
    }

    /**
     * @param $id
     *
     * @return Notification
     */
    private function getNotification($id)
    {
        $notifications = $this->getUser()->getUnreadNotifications();
        foreach ($notifications as $notification) {
            if ($notification->getId() == $id) {

                return $notification;
            }
        }

        throw $this->createNotFoundException();
    }

    /**
     * @return UserService
     */
    protected function getUserService()
    {
        return $this->get('talkly.service.user');
    }
}

