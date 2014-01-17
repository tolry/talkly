<?php

use Silex\Provider;

use Monolog\Logger;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use TobiasOlry\Talkly\Twig\MarkdownExtension;
use TobiasOlry\Talkly\Translator\NullTranslator;
use Salavert\Twig\Extension\TimeAgoExtension;


// config
$app->register(new DerAlex\Silex\YamlConfigServiceProvider(__DIR__ . '/../config/config.yml'));

$app['debug'] = $app['config']['debug'];

$app->register(new Provider\UrlGeneratorServiceProvider());
$app->register(new Provider\ServiceControllerServiceProvider());
$app->register(new Provider\FormServiceProvider());
$app->register(new Provider\TranslationServiceProvider(), array('locale_fallbacks' => array('en')));
$app->register(new Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../templates'));

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {

    $twig->addExtension(new MarkdownExtension());
    $twig->addExtension(new TimeAgoExtension(new NullTranslator()));

    $twig->getExtension('core')->setDateFormat('Y-m-d H:i:s', '%d days');

    return $twig;
}));

$app->register(new Provider\MonologServiceProvider(), array(
        'monolog.logfile' => __DIR__ . '/../logs/' . ($app['debug'] ? 'development' : 'prod') . '.log',
        'monolog.level'   => ($app['debug'] ? Logger::DEBUG : Logger::WARNING)
));

$app->register(new Provider\DoctrineServiceProvider, array(
    "db.options" => $app['config']['db.options']
));

$app->register(new DoctrineOrmServiceProvider, array(
    "orm.proxies_dir" => __DIR__ . '/cache/doctrine/orm/proxies',
    "orm.em.options" => array(
        "mappings" => array(
            // Using actual filesystem paths
            array(
                "type"            => "annotation",
                "namespace"       => "TobiasOlry\\Talkly\\Entity",
                "path"            => __DIR__."/../src/TobiasOlry/Talkly/Entity",
                "naming_strategy" => "doctrine.orm.naming_strategy.underscore",
            ),
        ),
    ),
));

if ($app['debug']) {
    $app->register(new Provider\WebProfilerServiceProvider(), array(
        'profiler.cache_dir'    => __DIR__.'/../cache/profiler',
        'profiler.mount_prefix' => '/_profiler', // this is the default
    ));
}

