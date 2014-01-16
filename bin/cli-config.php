<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

// cli-config.php
$app = require_once __DIR__ . '/../app/app.php';

return ConsoleRunner::createHelperSet($app['orm.em']);

