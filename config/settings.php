<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(dirname(__DIR__) . '/.env');
define('APP_ROOT', dirname(__DIR__));

return [
    'app' => [
        // Returns a detailed HTML page with error details and
        // a stack trace. Should be disabled in production.
        'display_error_details' => true,

        // Whether to display errors on the internal PHP log or not.
        'log_errors' => true,

        // If true, display full errors with message and stack trace on the PHP log.
        // If false, display only "Slim Application Error" on the PHP log.
        // Doesn't do anything when 'logErrors' is false.
        'log_error_details' => true,

        'project_dir' => APP_ROOT,

        'routes_dir' => APP_ROOT . '/routes',

        'api_routes' => APP_ROOT . '/routes/api.php',

        'middleware_registration' => APP_ROOT . '/middleware/registration.php',
    ],
    'doctrine' => [
        // Enables or disables Doctrine metadata caching
        // for either performance or convenience during development.
        'dev_mode' => true,

        // Path where Doctrine will cache the processed metadata
        // when 'dev_mode' is false.
        'cache_dir' => APP_ROOT . '/var/doctrine',

        // List of paths where Doctrine will search for metadata.
        // Metadata can be either YML/XML files or PHP classes annotated
        // with comments or PHP8 attributes.
        'metadata_dirs' => [APP_ROOT . '/src/Entity'],

        // The parameters Doctrine needs to connect to your database.
        // These parameters depend on the driver (for instance the 'pdo_sqlite' driver
        // needs a 'path' parameter and doesn't use most of the ones shown in this example).
        // Refer to the Doctrine documentation to see the full list
        // of valid parameters: https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html
        'connection' => [
            'dsn' => $_ENV['DSN']
        ]
    ]
];
