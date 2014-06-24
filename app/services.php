<?php

use TobiasOlry\Talkly\Service\TopicService;

$app['service.topic'] = $app->share(
    function () use ($app) {
        return new TopicService(
            $app['orm.em']
        );
    }
);
