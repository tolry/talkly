<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Markdown;

use Ciconia\Common\Text;
use Ciconia\Extension\ExtensionInterface;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TopicExtension implements ExtensionInterface
{
    private $urlGenerator;
    private $topicService;

    public function __construct(
        $urlGenerator,
        $topicService
    )
    {
        $this->urlGenerator = $urlGenerator;
        $this->topicService = $topicService;
    }

    /**
     * {@inheritdoc}
     */
    public function register(\Ciconia\Markdown $markdown)
    {
        $markdown->on('inline', array($this, 'processMentions'));
    }

    /**
     * @param Text $text
     */
    public function processMentions(Text $text)
    {
        $urlGenerator = $this->urlGenerator;
        $topicService = $this->topicService;

        // Turn #123 into [@username](http://example.com/user/username)
        $text->replace('/(?:^|[^a-zA-Z0-9])#([0-9]+)/', function (Text $w, Text $topicId) use ($urlGenerator, $topicService) {
            $topic      = $topicService->getTopic($topicId);
            $topicTitle = $topic->getTitle();
            $url        = $urlGenerator->generate(
                'topic-show',
                array('id' => $topicId),
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return " [#$topicId $topicTitle]($url)";
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'topic';
    }
}

