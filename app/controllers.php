<?php

use TobiasOlry\Talkly\Controller\IndexController;
use TobiasOlry\Talkly\Controller\TopicController;
use Symfony\Component\HttpFoundation\Response;

$app['index.controller'] = $app->share(
    function () use ($app) {
        return new IndexController(
            $app['twig'],
            $app['orm.em'],
            $app['form.factory'],
            $app['security.token']
        );
    }
);

$app['topic.controller'] = $app->share(
    function () use ($app) {
        return new TopicController(
            $app['orm.em'],
            $app['form.factory'],
            $app['url_generator'],
            $app['twig'],
            $app['security.token']
        );
    }
);
