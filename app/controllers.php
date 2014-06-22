<?php

use TobiasOlry\Talkly\Controller\IndexController;
use TobiasOlry\Talkly\Controller\TopicController;
use Symfony\Component\HttpFoundation\Response;

$app['controllers']
    ->before(function() use($app) {
        $request      = $app['request'];
        $userProvider = $app['user_provider'];

        if (! $user = $userProvider->getUsername()) {

            return new Response('not allowed', 403);
        }

        $request->server->set('PHP_AUTH_USER', $user);
    })
;

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
