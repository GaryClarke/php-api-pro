<?php

declare(strict_types=1);

namespace App\Http\Middleware\Security;

use App\Entity\User;
use App\Security\AccessControlManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpForbiddenException;

class PermissionsMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccessControlManager $accessControlManager,
        private string $permission
    ) {
    }

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $user = $request->getAttribute('user');
        assert($user instanceof User);

        if (!$this->accessControlManager->hasPermission($user->getRole(), $this->permission)) {
            throw new HttpForbiddenException($request, "You don't have the required permissions");
        }

        return $handler->handle($request);
    }
}
