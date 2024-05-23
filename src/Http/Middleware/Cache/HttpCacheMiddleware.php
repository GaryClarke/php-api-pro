<?php // src/Http/Middleware/Cache/HttpCacheMiddleware.php

declare(strict_types=1);

namespace App\Http\Middleware\Cache;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class HttpCacheMiddleware implements MiddlewareInterface
{
    public function __construct(
        private array $cacheControl = [],
        private ?int $expires = null
    ) {
    }

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        if (!empty($this->cacheControl)) {
            $response = $response->withHeader('Cache-Control', implode(', ', $this->cacheControl));
        }

        if ($this->expires) {
            $response = $response->withHeader('Expires', gmdate('D, d M Y H:i:s', time() + $this->expires) . ' GMT');
        }

        return $response;
    }
}