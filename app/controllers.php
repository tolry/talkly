<?php

use TobiasOlry\Talkly\Controller\IndexController;
use TobiasOlry\Talkly\Controller\TopicController;

$app['index.controller'] = $app->share(
    function () use ($app) {
        return new IndexController(
            $app['twig'],
            $app['form.factory'],
            $app['security.token'],
            $app['service.topic']
        );
    }
);

$app['topic.controller'] = $app->share(
    function () use ($app) {
        return new TopicController(
            $app['service.topic'],
            $app['form.factory'],
            $app['url_generator'],
            $app['twig'],
            $app['security.token']
        );
    }
);


