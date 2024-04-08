<?php

declare(strict_types=1);

namespace App\Http\Middleware\Utility;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MaintenanceModeMiddleware implements MiddlewareInterface
{
    public function __construct(private bool $isMaintenanceMode = false)
    {
    }

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        if (!$this->isMaintenanceMode) {
            return $handler->handle($request);
        }

        $response = new Response(StatusCodeInterface::STATUS_SERVICE_UNAVAILABLE);

        $response->getBody()->write('The API is currently down for maintenance');
        $response = $response->withHeader('Retry-After', 3600);

        return $response;
    }
}






