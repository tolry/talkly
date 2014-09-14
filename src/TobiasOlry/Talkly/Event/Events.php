<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Event;

class Events
{
    const TOPIC_CREATED        = 'talkly.topic.created';
    const COMMENT_CREATED      = 'talkly.comment.created';
    const TOPIC_ARCHIVED       = 'talkly.topic.archived';
    const TOPIC_SPEAKER_FOUND  = 'talkly.topic.speaker.found';
    const TOPIC_TALK_SCHEDULED = 'talkly.topic.talk.scheduled';
}
