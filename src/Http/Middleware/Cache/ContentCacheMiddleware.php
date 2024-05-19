<?php

declare(strict_types=1);

namespace App\Http\Middleware\Cache;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Symfony\Contracts\Cache\CacheInterface;

class ContentCacheMiddleware implements MiddlewareInterface
{
    public function __construct(private CacheInterface $cache)
    {
    }

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {

    }
}
