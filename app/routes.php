<?php

$app
    ->get('/', 'index.controller:dashboardAction')
    ->bind('homepage')
;

$app
    ->get('/archive', 'index.controller:archiveAction')
    ->bind('archive')
;

$app
    ->get('/calendar', 'index.controller:calendarAction')
    ->bind('calendar')
;

$app
    ->post('/topic/create', 'topic.controller:createAction')
    ->bind('topic-create')
;

$app
    ->get('/topic/{id}/show', 'topic.controller:showAction')
    ->bind('topic-show')
;

$app
    ->get('/topic/{id}/cast-vote', 'topic.controller:castVoteAction')
    ->bind('topic-cast-vote')
;

$app
    ->get('/topic/{id}/retract-vote', 'topic.controller:retractVoteAction')
    ->bind('topic-retract-vote')
;

$app
    ->post('/topic/{id}/comment', 'topic.controller:commentAction')
    ->bind('topic-comment')
;

$app
    ->post('/topic/{id}/archive', 'topic.controller:archiveAction')
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
