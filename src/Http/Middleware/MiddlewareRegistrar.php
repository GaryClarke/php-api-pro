<?php // src/Http/Middleware/MiddlewareRegistrar.php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Error\HttpErrorHandler;
use App\Http\Middleware\ContentNegotiation\ContentTypeMiddleware;
use App\Http\Middleware\ContentNegotiation\ContentTypeNegotiator;
use App\Http\Middleware\Utility\MaintenanceModeMiddleware;
use App\Serializer\Serializer;
use Middlewares\TrailingSlash;
use Psr\Log\LoggerInterface;
use Slim\App;

/**
 * Registers middleware
 *
 * Order is important.
 */
final readonly class MiddlewareRegistrar
{
    public function __construct(
        private App $app
    ) {
    }

    public function register(): void
    {
        $this->registerCustomMiddleware();
        $this->registerDefaultMiddleware();
        $this->addErrorMiddleware();
    }

    private function registerCustomMiddleware(): void
    {
        $app = $this->app;
        $container = $app->getContainer();

        $serializer = $container->get(Serializer::class);
        // .. register custom middleware here
        $app->add(new ContentTypeMiddleware(new ContentTypeNegotiator($serializer)));
        $app->add(new MaintenanceModeMiddleware($container->get('maintenance_mode')));
    }

    private function registerDefaultMiddleware(): void
    {
        $app = $this->app;

        $app->addBodyParsingMiddleware();
        $app->addRoutingMiddleware();
        $app->add(new TrailingSlash(false));
    }

    private function addErrorMiddleware(): void
    {
        $logger = $this->app->getContainer()->get(LoggerInterface::class);
        $errorMiddleware = $this->app->addErrorMiddleware(true, true, true, $logger);
        $callableResolver = $this->app->getCallableResolver();
        $responseFactory = $this->app->getResponseFactory();
        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory, $logger);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);
    }
}
