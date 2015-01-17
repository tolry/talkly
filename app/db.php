<?php

use Silex\Provider\DoctrineServiceProvider;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use TobiasOlry\Talkly\Doctrine\ManagerRegistry;

$app->register(new DoctrineServiceProvider, array(
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
    "orm.em.options"  => array(
        "mappings" => [
            [
                "type"                         => "annotation",
                "use_simple_annotation_reader" => false,
                "namespace"                    => "TobiasOlry\\Talkly\\Entity",
                "path"                         => __DIR__ . "/../src/TobiasOlry/Talkly/Entity",
                "naming_strategy"              => "doctrine.orm.naming_strategy.underscore",
            ],
        ],
    ),
));

$app['orm.registry'] = $app->share(function () use ($app) {
    $manager = new ManagerRegistry(null, array(), array('orm.em'), null, null, '\Doctrine\ORM\Proxy\Proxy');
    $manager->setContainer($app);

    return $manager;
});

$app['form.extensions'] = $app->share($app->extend('form.extensions', function ($extensions, $app) {
    $extensions[]    = new DoctrineOrmExtension($app['orm.registry']);

    return $extensions;
}));
