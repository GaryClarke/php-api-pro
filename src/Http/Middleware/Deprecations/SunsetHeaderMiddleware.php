<?php

declare(strict_types=1);

namespace App\Http\Middleware\Deprecations;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class SunsetHeaderMiddleware implements MiddlewareInterface
{
    public function __construct(
        private string $date
    ) {
    }

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        return $response->withHeader('Sunset', sprintf('date="%s"', $this->date));
    }
}
