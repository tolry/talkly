<?php

namespace TobiasOlry\Talkly\Twig;

use Ciconia\Ciconia;

/**
 *
 * @author Tobias Olry <tobias.olry@gmail.com>
 * @author David Badura <d.a.badura@gmail.com>
 */
class MarkdownExtension extends \Twig_Extension
{
    /**
     *
     * @var Ciconia
     */
    protected $engine;

    /**
     *
     * @param Ciconia $engine
     */
    public function __construct(Ciconia $engine)
    {
        $this->engine = $engine;
    }

    /**
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('markdown', array($this, 'renderMarkdown'), array('is_safe' => array('html'))),
        );
    }

    /**
     *
     * @param string $string
     * @return string
     */
    public function renderMarkdown($string)
    {
        return $this->engine->render($string);
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'markdown';
    }
}

