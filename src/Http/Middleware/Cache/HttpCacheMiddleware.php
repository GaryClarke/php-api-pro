<?php

declare(strict_types=1);

namespace App\Http\Middleware\Cache;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class HttpCacheMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        return $handler->handle($request);
    }
}