<?php

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

$app
    ->get('/', 'index.controller:dashboard')
    ->bind('homepage')
;

$app
    ->get('/archive', 'index.controller:archive')
    ->bind('archive')
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

$app
    ->post('/topic/{id}/comment', 'topic.controller:comment')
    ->bind('topic-comment')
;


