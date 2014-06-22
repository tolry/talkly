<?php

use Symfony\Component\Console\Application;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;

$app['doctrine.commands'] = array(
    'command.doctrine.migration.diff'     => 'Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand',
    'command.doctrine.migration.execute'  => 'Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand',
    'command.doctrine.migration.generate' => 'Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand',
    'command.doctrine.migration.migrate'  => 'Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand',
    'command.doctrine.migration.status'   => 'Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand',
    'command.doctrine.migration.version'  => 'Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand'
);


$app['console.application'] = $app->share(
    function () use ($app) {
        $application = new Application();

        foreach (array_keys($app['doctrine.commands']) as $id) {
            $application->add($app[$id]);
        }

        $application->getHelperSet()->set(new ConnectionHelper($app['db']));
        $application->getHelperSet()->set(new EntityManagerHelper($app['orm.em']), 'em');

        return $application;
    }
);

foreach ($app['doctrine.commands'] as $id => $class) {
    $app[$id] = $app->share(
        function () use ($app, $class) {
            $command = new $class();
            $command->setMigrationConfiguration($app['doctrine.migration.configuration']);

            return $command;
        }
    );
}

$app['doctrine.migration.configuration'] = $app->share(
    function () use ($app) {
        $conf = new Configuration($app['db']);
        $conf->setMigrationsDirectory(__DIR__ . '/DoctrineMigrations');
        $conf->setMigrationsNamespace('DoctrineMigrations');
        $conf->registerMigrationsFromDirectory($conf->getMigrationsDirectory());

        return $conf;
    }
);
