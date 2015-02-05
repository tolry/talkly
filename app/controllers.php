<?php

use TobiasOlry\Talkly\Controller\Api;
use TobiasOlry\Talkly\Controller\IndexController;
use TobiasOlry\Talkly\Controller\TopicController;
use TobiasOlry\Talkly\Controller\UserController;
use TobiasOlry\Talkly\Controller\AjaxController;

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

$app['user.controller'] = $app->share(
    function () use ($app) {
        return new UserController(
            $app['service.user'],
            $app['form.factory'],
            $app['url_generator'],
            $app['twig'],
            $app['security.token']
        );
    }
);

$app['api.topic.controller'] = $app->share(
    function () use ($app) {
        return new Api\TopicController(
            $app['service.topic'],
            $app['url_generator']
        );
    }
);

$app['api.security.controller'] = $app->share(
    function () use ($app) {
        return new Api\SecurityController($app['security.token']);
    }
);

$app['ajax.controller'] = $app->share(
    function () use ($app) {
        return new AjaxController(
            $app['markdown']
        );
    }
);
