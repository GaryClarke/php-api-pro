<?php

declare(strict_types=1);

namespace App\Http\Middleware\ContentNegotiation;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

readonly class ContentTypeMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ContentTypeNegotiator $contentNegotiator
    ) {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $request = $this->contentNegotiator->negotiate($request);

        // Handle the request..returns a Response
        $response = $handler->handle($request);

        // Return the response
        return $response->withHeader('Content-Type', $request->getAttribute('content-type'));
    }
}
