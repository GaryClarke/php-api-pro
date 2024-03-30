<?php

declare(strict_types=1);

namespace App\Http\Error;

use App\Http\Error\Exception\ExtensibleExceptionInterface;
use App\Http\Error\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler;
use Exception;
use Throwable;

class HttpErrorHandler extends ErrorHandler
{
    protected function respond(): ResponseInterface
    {
        $exception = $this->exception;
        $statusCode = 500;
        $problem = ProblemDetail::INTERNAL_SERVER_ERROR;
        $description = 'An internal error has occurred while processing your request.';
        $title = '500 Internal Server Error';

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $description = $exception->getDescription();
            $title = $exception->getTitle();

            $problem = ProblemDetail::tryFrom($exception->getCode()) ?? ProblemDetail::BAD_REQUEST;
        }

        if (
            !($exception instanceof HttpException)
            && ($exception instanceof Throwable)
            && $this->displayErrorDetails
        ) {
            $description = $exception->getMessage();
        }

        $error = [
            'type' => $problem->type(),
            'title' => $title,
            'detail' => $description,
            'instance' => $this->request->getUri()->getPath(),
        ];
        # extensions (examples) - Use custom exceptions for these and array merge the extensions
        if ($exception instanceof ExtensibleExceptionInterface) {
            $error += $exception->getExtensions();
        }

        $payload = json_encode($error, JSON_PRETTY_PRINT);

        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write($payload);
        $response = $response->withHeader('Content-Type', 'application/problem+json');

        return $response;
    }
}