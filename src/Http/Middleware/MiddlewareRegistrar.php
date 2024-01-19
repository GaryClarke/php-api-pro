<?php

declare(strict_types=1);

namespace App\Http\Middleware;

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
        $this->app->addErrorMiddleware(true, true, true);
    }
}
