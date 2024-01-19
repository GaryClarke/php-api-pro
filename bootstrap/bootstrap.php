<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

$appSettings = $container->get('settings')['app'];

// Define routes
include $appSettings['api_routes'];

// Define middleware
include $appSettings['middleware_registration'];


