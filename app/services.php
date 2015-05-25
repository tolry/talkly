<?php

use TobiasOlry\Talkly\Event\NotificationTransport\DatabaseTransport;
use TobiasOlry\Talkly\Event\NotificationTransport\EmailTransport;
use TobiasOlry\Talkly\Event\Subscriber\NotificationSubscriber;
use TobiasOlry\Talkly\Markdown\TopicExtension;
use TobiasOlry\Talkly\Service\TopicService;
use TobiasOlry\Talkly\Service\UserService;


$app['service.notificationtransport.email'] = $app->share(
    function () use ($app) {
        return new EmailTransport(
            $app['mailer'],
            $app['twig'],
            $app['config']['notification_email_sender']
        );
    }
);

$app['service.notificationtransport.db'] = $app->share(
    function () use ($app) {
        return new DatabaseTransport(
            $app['service.user']
        );
    }
);

$app['service.eventsubscriber'] = $app->share(
    function () use ($app) {
        $subscriber = new NotificationSubscriber(
            $app['service.user'],
            $app['service.topic'],
            $app['security.token']
        );

        $subscriber->addTransport($app['service.notificationtransport.email']);
        $subscriber->addTransport($app['service.notificationtransport.db']);

        return $subscriber;
    }
);

$app['service.markdown.extension.topic'] = $app->share(
    function () use ($app) {
        return new TopicExtension(
            $app['url_generator'],
            $app['service.topic']
        );
    }
);

