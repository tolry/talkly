<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Twig;

use \Michelf\Markdown;
use \Michelf\MarkdownExtra;

class MarkdownExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('markdown', array($this, 'renderMarkdown'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('markdown_extra', array($this, 'renderMarkdownExtra'), array('is_safe' => array('html'))),
        );
    }

    public function renderMarkdown($string)
    {
        return Markdown::defaultTransform($string);
    }

    public function renderMarkdownExtra($string)
    {
        return MarkdownExtra::defaultTransform($string);
    }

    public function getName()
    {
        return 'markdown';
    }
}

