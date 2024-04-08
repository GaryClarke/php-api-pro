<?php

declare(strict_types=1);

namespace App\Http\Middleware\Utility;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class MaintenanceModeMiddleware implements MiddlewareInterface
{
    public function __construct(private bool $isMaintenanceMode = false)
    {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        if (!$this->isMaintenanceMode) {
            return $handler->handle($request);
        }

        dd($this->isMaintenanceMode);

        // ...
    }
}






