<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Error\HttpErrorHandler;
use App\Http\Middleware\ContentNegotiation\ContentTypeMiddleware;
use App\Http\Middleware\ContentNegotiation\ContentTypeNegotiator;
use Middlewares\TrailingSlash;
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

        // .. register custom middleware here
        $app->add(new ContentTypeMiddleware(new ContentTypeNegotiator()));
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
        $errorMiddleware = $this->app->addErrorMiddleware(true, true, true);
        $callableResolver = $this->app->getCallableResolver();
        $responseFactory = $this->app->getResponseFactory();
        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);
    }
}
