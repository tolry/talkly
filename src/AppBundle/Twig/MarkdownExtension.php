<?php

namespace AppBundle\Twig;

use League\CommonMark\Converter;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class MarkdownExtension extends \Twig_Extension
{
    /**
     * @var Converter
     */
    private $converter;

    /**
     * @param Converter $converter
     */
    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('markdown', [$this->converter, 'convertToHtml'], ['is_safe' => ['html']])
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'markdown';
    }
}