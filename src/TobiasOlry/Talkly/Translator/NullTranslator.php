<?php
/*
 *
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Translator;

class NullTranslator
{
    public function trans($message, array $placeholders = array())
    {
        return str_replace(
            array_keys($placeholders),
            array_values($placeholders),
            $message
        );
    }
}


