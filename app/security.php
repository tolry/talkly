<?php

use Symfony\Component\HttpFoundation\Response;
use TobiasOlry\Talkly\Security\UserProvider\DebugUserProvider;
use TobiasOlry\Talkly\Security\UserProvider\NtlmUserProvider;
use TobiasOlry\Talkly\Security\Security;
use TobiasOlry\Talkly\Security\UserManager;
use TobiasOlry\Talkly\Twig\SecurityExtension;

use TobiasOlry\Talkly\Event\Subscriber\NotificationSubscriber;
use TobiasOlry\Talkly\Event\notificationtransport\EmailTransport;

$app['controllers']->before(
    function () use ($app) {
        $request      = $app['request'];
        $userProvider = $app['security.user_provider'];

        if (! $username = $userProvider->getUsername()) {
            return new Response('not allowed', 403);
        }

        $user = $app['security.usermanager']->findOrCreate($username);

        $request->server->set('PHP_AUTH_USER', $username);
        $app['security.token']->setUser($user);

        $app['dispatcher']->addSubscriber($app['service.eventsubscriber']);
    }
);


$app['security.usermanager'] = $app->share(
    function () use ($app) {
        return new UserManager($app['orm.em']);
    }
);

