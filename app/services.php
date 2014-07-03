<?php

use TobiasOlry\Talkly\Service\TopicService;
use TobiasOlry\Talkly\Service\UserService;

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
            $app['orm.em']
        );
    }
);
