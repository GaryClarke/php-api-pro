<?php

declare(strict_types=1);

namespace App\Http\Middleware\Utility;

use _PHPStan_3d4486d07\Fig\Http\Message\StatusCodeInterface;
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

        $response = new \Slim\Psr7\Response(StatusCodeInterface::STATUS_SERVICE_UNAVAILABLE);

        $response->getBody()->write('The API is currently down for maintenance');
        $response = $response->withHeader('Retry-After', 3600);

        return $response;
    }
}






