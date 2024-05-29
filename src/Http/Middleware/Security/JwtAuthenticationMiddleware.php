<?php

declare(strict_types=1);

namespace App\Http\Middleware\Security;

use App\Security\TokenAuthenticator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpUnauthorizedException;

class JwtAuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private TokenAuthenticator $tokenAuthenticator
    ) {
    }

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        // Grab the authorization header
        $header = $request->getHeaderLine('Authorization');

        // Error if Bearer token not present
        if (!preg_match('/Bearer\s+(\S+)/', $header, $matches)) {
            throw new HttpUnauthorizedException($request, "Unable to authenticate");
        }

        try {
            $jwt = $matches[1];

            // Authenticate jwt
            $payload = $this->tokenAuthenticator->authenticate($jwt);

            dd($payload);

        } catch (\Exception $exception) {
            throw new HttpUnauthorizedException($request, "Unable to authenticate");
        }
    }
}