<?php

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
    ->get('/topic/{id}/show', 'topic.controller:show')
    ->bind('topic-show')
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

$app
    ->post('/topic/{id}/archive', 'topic.controller:archive')
    ->bind('topic-archive')
;

$app
    ->get('/topic/{id}/add-speaker', 'topic.controller:addSpeakerAction')
    ->bind('topic-add-speaker')
;

$app
    ->get('/topic/{id}/remove-speaker', 'topic.controller:removeSpeakerAction')
    ->bind('topic-remove-speaker')
;
