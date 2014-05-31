<?php

use Symfony\Component\HttpFoundation\Response;

$app['controllers']
    ->before(function() use($app) {
        $twig    = $app['twig'];
        $request = $app['request'];

        $user = $app['user_provider']->getUsername();

        if (! $user) {
            // @todo return RedirectResponse
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


