<?php

$loader = require_once __DIR__.'/../vendor/autoload.php';

// catch uncatchable fatal errors
Symfony\Component\Debug\Debug::enable();
Symfony\Component\Debug\ErrorHandler::register();

$app = new Silex\Application();
$app['loader'] = $loader;

require_once __DIR__ . '/providers.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/controllers.php';
require_once __DIR__ . '/routes.php';
require_once __DIR__ . '/commands.php';
require_once __DIR__ . '/services.php';

return $app;
