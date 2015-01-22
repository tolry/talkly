<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Criteria;

class TopicCriteria
{
    public $archived;

    public function __construct($archived = null)
    {
        $this->archived = $archived;
    }
}

