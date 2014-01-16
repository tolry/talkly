<?php

$app['controllers']
    ->before(function() use($app) {
        $twig    = $app['twig'];
        $request = $app['request'];

        $request->server->set('PHP_AUTH_USER', 'korgano');

        $twig->addGlobal('request', $request);
    })
;

$app
    ->get('/', 'index.controller:dashboard')
    ->bind('homepage')
;

$app
    ->post('/topic/create', 'topic.controller:create')
    ->bind('topic-create')
;
