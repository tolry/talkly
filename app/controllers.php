<?php

use TobiasOlry\Talkly\Controller\IndexController;
use TobiasOlry\Talkly\Controller\TopicController;;

$app['index.controller'] = $app->share(function() use ($app) {
    return new IndexController(
        $app['twig'],
        $app['orm.em'],
        $app['form.factory']
    );
});

$app['topic.controller'] = $app->share(function() use ($app) {
    return new TopicController(
        $app['orm.em'],
        $app['form.factory'],
        $app['url_generator'],
        $app['twig']
    );
});
