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

$app
    ->get('/topic/{id}/cast-vote', 'topic.controller:castVote')
    ->bind('topic-cast-vote')
;

$app
    ->get('/topic/{id}/retract-vote', 'topic.controller:retractVote')
    ->bind('topic-retract-vote')
;


