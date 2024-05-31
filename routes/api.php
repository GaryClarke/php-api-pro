<?php // routes/api.php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Handlers\Strategies\RequestResponseArgs;

require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Changing the default invocation strategy on the RouteCollector component
 * will change it for every route being defined after this change being applied
 */
$routeCollector = $app->getRouteCollector();
$routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());

$container = $app->getContainer();

// Define routes
$app->get('/healthcheck', function (Request $request, Response $response) {
    $payload = json_encode(['app' => true]);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->group('/flights', function (\Slim\Routing\RouteCollectorProxy $group) use ($container) {
    $group->get('', [\App\Controller\FlightsController::class, 'index']);

    $group->get(
        '/{number:[A-Za-z]{2}[0-9]{1,4}-[0-9]{8}}',
        [\App\Controller\FlightsController::class, 'show']
    )->addMiddleware(new \App\Http\Middleware\Cache\HttpCacheMiddleware(
        cacheControl: ['public', 'max-age=600', 'must-revalidate'],
        expires: 600,
        vary: ['Accept-Encoding']
    ));

    $group->post('', [\App\Controller\FlightsController::class, 'store']);

    $group->delete(
        '/{number:[A-Za-z]{2}[0-9]{1,4}-[0-9]{8}}',
        [\App\Controller\FlightsController::class, 'destroy']
    );

    $group->put(
        '/{number:[A-Za-z]{2}[0-9]{1,4}-[0-9]{8}}',
        [\App\Controller\FlightsController::class, 'update']
    );

    $group->patch(
        '/{number:[A-Za-z]{2}[0-9]{1,4}-[0-9]{8}}',
        [\App\Controller\FlightsController::class, 'update']
    );
});

$app->group('/passengers', function (\Slim\Routing\RouteCollectorProxy $group) {
    $group->get('', [\App\Controller\PassengersController::class, 'index']);

    $group->get(
        '/{reference:[0-9]+[A-Z]{3}}',
        [\App\Controller\PassengersController::class, 'show']
    );

    $group->post('', [\App\Controller\PassengersController::class, 'store']);

    $group->delete(
        '/{reference:[0-9]+[A-Z]{3}}',
        [\App\Controller\PassengersController::class, 'destroy']
    );

    $group->put(
        '/{reference:[0-9]+[A-Z]{3}}',
        [\App\Controller\PassengersController::class, 'update']
    );

    $group->patch(
        '/{reference:[0-9]+[A-Z]{3}}',
        [\App\Controller\PassengersController::class, 'update']
    );
});

$app->group('', function (\Slim\Routing\RouteCollectorProxy $group) use ($container) {
    // GET collection /flights/<number>/reservations
    $group->get(
        '/flights/{number:[A-Za-z]{2}[0-9]{1,4}-[0-9]{8}}/reservations',
        [\App\Controller\ReservationsController::class, 'index']
    )->addMiddleware(new \App\Http\Middleware\Cache\ContentCacheMiddleware(
        $container->get(\Symfony\Contracts\Cache\CacheInterface::class)
    ));

    // POST /flights/<number>/reservations
    $group->post(
        '/flights/{number:[A-Za-z]{2}[0-9]{1,4}-[0-9]{8}}/reservations',
        [\App\Controller\ReservationsController::class, 'store']
    )->addMiddleware(
        new \App\Http\Middleware\Security\PermissionsMiddleware(
            $container->get(\App\Security\AccessControlManager::class),
            \App\Security\AccessControlManager::CREATE_RESERVATION
        )
    )->addMiddleware(
        new \App\Http\Middleware\Security\JwtAuthenticationMiddleware(
        $container->get(\App\Security\TokenAuthenticator::class)
    ));

    // GET item, DELETE, PUT, PATCH
    // reservations/<reference>
    $group->get(
        '/reservations/{reference:[0-9]+JF[0-9]{4}}',
        [\App\Controller\ReservationsController::class, 'show']
    );

    $group->delete(
        '/reservations/{reference:[0-9]+JF[0-9]{4}}',
        [\App\Controller\ReservationsController::class, 'destroy']
    );

    $group->map(
        ['PUT', 'PATCH'],
        '/reservations/{reference:[0-9]+JF[0-9]{4}}',
        [\App\Controller\ReservationsController::class, 'update']
    )->addMiddleware(new \App\Http\Middleware\Security\JwtAuthenticationMiddleware(
        $container->get(\App\Security\TokenAuthenticator::class)
    ));
});











