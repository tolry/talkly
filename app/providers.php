<?php

use Silex\Provider;

use Monolog\Logger;
use TobiasOlry\Talkly\Twig\MarkdownExtension;
use TobiasOlry\Talkly\Twig\GravatarExtension;
use TobiasOlry\Talkly\Translator\NullTranslator;
use Salavert\Twig\Extension\TimeAgoExtension;
use Ciconia\Ciconia;
use Ciconia\Extension\Gfm;

// config

if (! file_exists(__DIR__ . '/config/config.yml')) {
    throw new \RuntimeException('you need to generate a config file first, just copy and edit app/config/config.dist.yml to app/config/config.yml');
}
$app->register(new DerAlex\Silex\YamlConfigServiceProvider(__DIR__ . '/config/config.yml'));
$app['config'] = $app['config']['parameters'];
$app['debug']  = $app['config']['debug'];

$app->register(new Silex\Provider\SessionServiceProvider(array('name' => 'TALKLY_SESSION')));
$app->register(new Provider\UrlGeneratorServiceProvider());
$app->register(new Provider\ServiceControllerServiceProvider());
$app->register(new Provider\FormServiceProvider());
$app->register(new Provider\TranslationServiceProvider(), array('locale_fallbacks' => array('en')));
$app->register(new Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../templates'));

$app['markdown'] = $app->share(function() use ($app) {
    $ciconia = new Ciconia();
    $ciconia->addExtension(new Gfm\FencedCodeBlockExtension());
    $ciconia->addExtension(new Gfm\TaskListExtension());
    $ciconia->addExtension(new Gfm\InlineStyleExtension());
    $ciconia->addExtension(new Gfm\WhiteSpaceExtension());
    $ciconia->addExtension(new Gfm\TableExtension());
    $ciconia->addExtension(new Gfm\UrlAutoLinkExtension());

    return $ciconia;
});

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {

    $twig->addExtension(new MarkdownExtension($app['markdown']));
    $twig->addExtension(new TimeAgoExtension(new NullTranslator()));
    $twig->addExtension(new GravatarExtension());

    $twig->getExtension('core')->setDateFormat('Y-m-d H:i:s', '%d days');

    return $twig;
}));

$app->register(new Provider\MonologServiceProvider(), array(
        'monolog.logfile' => __DIR__ . '/logs/' . ($app['debug'] ? 'development' : 'prod') . '.log',
        'monolog.level'   => ($app['debug'] ? Logger::DEBUG : Logger::WARNING)
));

if ($app['debug']) {
    $app->register(new Provider\WebProfilerServiceProvider(), array(
        'profiler.cache_dir'    => __DIR__.'/cache/profiler',
        'profiler.mount_prefix' => '/_profiler', // this is the default
    ));
}
