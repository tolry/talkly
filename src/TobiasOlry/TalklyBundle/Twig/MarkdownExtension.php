<?php

namespace TobiasOlry\TalklyBundle\Twig;

use Ciconia\Ciconia;

/**
 *
 * @author Tobias Olry <tobias.olry@gmail.com>
 * @author David Badura <d.a.badura@gmail.com>
 */
class MarkdownExtension extends \Twig_Extension
{
    /**
     * @var Ciconia
     */
    protected $engine;

    /**
     * @param Ciconia $engine
     */
    public function __construct(Ciconia $engine)
    {
        $this->engine = $engine;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('markdown', [$this, 'renderMarkdown'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function renderMarkdown($string, $inline = false)
    {
        $html = $this->engine->render($string);

        if ($inline) {
            $html = str_replace(['<p>', '</p>'], '', $html);
        }

        return $html;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'markdown';
    }
}
