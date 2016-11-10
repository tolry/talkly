<?php

namespace AppBundle\Markdown;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class UrlPaserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function urls()
    {
        return [
            ['http://google.com', true],
            ['https://google.com', true],
            ['file://google.com', true],
            ['file://www.google.com', true],
            ['www.google.com', true],
            ['google.com', false],
            ['//google.com', false],
        ];
    }

    /**
     * @param string $url
     * @param bool $result
     *
     * @dataProvider urls
     */
    public function testUrlPattern($url, $result)
    {
        self::assertEquals($result, (bool)preg_match(UrlParser::PATTERN, $url, $matches, PREG_OFFSET_CAPTURE));
    }
}