<?php

declare(strict_types=1);

namespace App\Http\Middleware\Security;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpUnauthorizedException;

class JwtAuthenticationMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        // Grab the authorization header
        $header = $request->getHeaderLine('Authorization');

        // Error if Bearer token not present
        if (!preg_match('/Bearer\s+(\S+)/', $header, $matches)) {
            throw new HttpUnauthorizedException($request, "Unable to authenticate");
        }

        dd($header, $matches[1]);
    }
}