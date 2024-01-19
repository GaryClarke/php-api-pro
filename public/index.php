<?php

use Slim\Factory\AppFactory;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$container = require_once dirname(__DIR__) . '/config/di.php';

AppFactory::setContainer($container);

// Instantiate and bootstrap the app
$app = AppFactory::create();

include dirname(__DIR__) . '/bootstrap/bootstrap.php';

$app->run();
