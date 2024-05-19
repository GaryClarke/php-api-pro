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
        $filters = $request->getQueryParams();

        $shouldCache = empty(array_diff(array_keys($filters), ['page']));

        $cacheKey = str_replace('/', '', $request->getUri()->getPath()) . ".page=" . ($filters['page'] ?? 1);

        if ($shouldCache) {
            $cacheItem = $this->cache->getItem($cacheKey);

            if ($cacheItem->isHit()) {
                $response = new Response();
                $response->getBody()->write($cacheItem->get());
                return $response->withHeader('Cache-Control', 'public, max-age=600');
            }
        }

        $response = $handler->handle($request);

        $response->getBody()->rewind();
        $content = $response->getBody()->getContents();

        // caching
        if ($shouldCache) {
            $cacheItem->set($content);
            $cacheItem->expiresAfter(600);
            $this->cache->save($cacheItem);
        }

        return $response;
    }
}
