<?php

use Silex\Provider;

use Monolog\Logger;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use TobiasOlry\Talkly\Twig\MarkdownExtension;
use TobiasOlry\Talkly\Translator\NullTranslator;
use TobiasOlry\Talkly\UserProvider\DebugUserProvider;
use TobiasOlry\Talkly\UserProvider\NtlmUserProvider;
use Salavert\Twig\Extension\TimeAgoExtension;


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

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {

    $twig->addExtension(new MarkdownExtension());
    $twig->addExtension(new TimeAgoExtension(new NullTranslator()));

    $twig->getExtension('core')->setDateFormat('Y-m-d H:i:s', '%d days');

    return $twig;
}));

$app->register(new Provider\MonologServiceProvider(), array(
        'monolog.logfile' => __DIR__ . '/logs/' . ($app['debug'] ? 'development' : 'prod') . '.log',
        'monolog.level'   => ($app['debug'] ? Logger::DEBUG : Logger::WARNING)
));

$app->register(new Provider\DoctrineServiceProvider, array(
    "db.options" => array(
        'driver'   => $app['config']['db_driver'],
        'host'     => $app['config']['db_host'],
        'port'     => $app['config']['db_port'],
        'dbname'   => $app['config']['db_dbname'],
        'user'     => $app['config']['db_user'],
        'password' => $app['config']['db_password'],
        'charset'  => $app['config']['db_charset'],
    )
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
        'profiler.cache_dir'    => __DIR__.'/cache/profiler',
        'profiler.mount_prefix' => '/_profiler', // this is the default
    ));
}

switch($app['config']['user_provider']) {
    case 'debug':
        $app['user_provider'] = $app->share(function() use ($app) {
            $user = isset($app['config']['user_provider_debug_user']) ? $app['config']['user_provider_debug_user'] : 'Max Musernman';
            return new DebugUserProvider($user);
        });
    break;
    case 'ntlm':
        $app['user_provider'] = $app->share(function() use ($app) {
            return new NtlmUserProvider(
                $app['request_stack'],
                $app['config']['user_provider_ntlm_domain']
            );
        });
    break;
}

