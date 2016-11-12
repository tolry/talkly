<?php

namespace AppBundle\Markdown;

use League\CommonMark\Inline\Element\Link;
use League\CommonMark\Inline\Parser\AbstractInlineParser;
use League\CommonMark\InlineParserContext;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class UrlParser extends AbstractInlineParser
{
    const PATTERN = '#(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))#iS';

    /**
     * @return string[]
     */
    public function getCharacters()
    {
        return ['h', 'f', 'w'];
    }

    /**
     * @param InlineParserContext $inlineContext
     *
     * @return bool
     */
    public function parse(InlineParserContext $inlineContext)
    {
        $cursor = $inlineContext->getCursor();
        $previousState = $cursor->saveState();
        $url = $cursor->match(self::PATTERN);

        if (!$url) {
            $cursor->restoreState($previousState);

            return false;
        }

        $label = $url;

        if (strpos($url, 'www.') === 0) {
            $url = 'http://' . $url;
        }

        $inlineContext->getContainer()->appendChild(new Link($url, $label));

        return true;
    }
}
