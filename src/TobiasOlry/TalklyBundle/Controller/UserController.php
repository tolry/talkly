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

        return $this->json($user, 200, [], ['groups' => ['user_show']]);
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

        return $this->json($user, 200, [], ['groups' => ['user_show']]);
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
     * @Route("/user/notifications", name="user-notifications")
     * @Template()
     *
     * @return Response
     */
    public function notificationsAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json($user->getUnreadNotifications(), 200, [], ['groups' => ['notification']]);
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

