<?php
namespace TobiasOlry\TalklyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TobiasOlry\TalklyBundle\Entity\Notification;
use TobiasOlry\TalklyBundle\Entity\User;

/**
 * @author Tobias Olry <olry@gmail.com>
 * @author David Badura <d.a.badura@gmail.com>
 *
 * @Route("/notification")
 */
class NotificationController extends Controller
{
    /**
     * @Route("/")
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
     * @Route("/{id}/mark-read", name="user-notification-read")
     * @param Request $request
     *
     * @return Response
     */
    public function markNotificationReadAction(Request $request)
    {
        $notification = $this->getNotification($request->get('id'));
        $notification->markAsDone();

        $this->get('doctrine')->getManager()->flush();

        return $this->json(true);
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
}

