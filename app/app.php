<?php

$loader = require_once __DIR__.'/../vendor/autoload.php';

// catch uncatchable fatal errors
Symfony\Component\Debug\Debug::enable();
Symfony\Component\Debug\ErrorHandler::register();

$app = new Silex\Application();
$app['loader'] = $loader;

require_once __DIR__ . '/providers.php';
require_once __DIR__ . '/controllers.php';
require_once __DIR__ . '/routes.php';

return $app;
