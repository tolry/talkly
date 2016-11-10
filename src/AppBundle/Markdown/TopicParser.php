<?php

namespace AppBundle\Markdown;

use AppBundle\Entity\Topic;
use AppBundle\Repository\TopicRepository;
use League\CommonMark\Inline\Element\Link;
use League\CommonMark\Inline\Parser\AbstractInlineParser;
use League\CommonMark\InlineParserContext;
use Symfony\Component\Routing\Router;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class TopicParser extends AbstractInlineParser
{
    /**
     * @var TopicRepository
     */
    private $topicRepository;

    /**
     * @var Router
     */
    private $router;

    /**
     * @param TopicRepository $topicRepository
     * @param Router $router
     */
    public function __construct(TopicRepository $topicRepository, Router $router)
    {
        $this->topicRepository = $topicRepository;
        $this->router = $router;
    }

    /**
     * @return string[]
     */
    public function getCharacters()
    {
        return ['#'];
    }

    /**
     * @param InlineParserContext $inlineContext
     *
     * @return bool
     */
    public function parse(InlineParserContext $inlineContext)
    {
        $cursor = $inlineContext->getCursor();
        $previousChar = $cursor->peek(-1);

        if ($previousChar !== null && $previousChar !== ' ') {
            return false;
        }

        $previousState = $cursor->saveState();
        $cursor->advance();
        $handle = $cursor->match('/^[0-9]+/');

        if (!$handle) {
            $cursor->restoreState($previousState);

            return false;
        }

        /** @var Topic $topic */
        $topic = $this->topicRepository->find($handle);

        if (!$topic) {
            $cursor->restoreState($previousState);

            return false;
        }

        $url = $this->router->generate('topic', ['id' => $topic->getId()], Router::ABSOLUTE_URL);
        $inlineContext->getContainer()->appendChild(new Link($url, $topic->getTitle()));

        return true;
    }
}
