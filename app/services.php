<?php

use TobiasOlry\Talkly\Service\TopicService;
use TobiasOlry\Talkly\Service\UserService;
use TobiasOlry\Talkly\Event\Subscriber\NotificationSubscriber;
use TobiasOlry\Talkly\Event\NotificationTransport\EmailTransport;

$app['service.user'] = $app->share(
    function () use ($app) {
        return new UserService(
            $app['orm.em']
        );
    }
);

$app['service.topic'] = $app->share(
    function () use ($app) {
        return new TopicService(
            $app['orm.em'],
            $app['dispatcher']
        );
    }
);

$app['service.notificationtransport.email'] = $app->share(
    function () use ($app) {
        return new EmailTransport(
            $app['mailer'],
            $app['config']['notification_email_sender']
        );
    }
);

$app['service.eventsubscriber'] = $app->share(
    function () use ($app) {
        $subscriber = new NotificationSubscriber(
            $app['service.user'],
            $app['service.topic']
        );

        $subscriber->addTransport($app['service.notificationtransport.email']);

        return $subscriber;
    }
);
