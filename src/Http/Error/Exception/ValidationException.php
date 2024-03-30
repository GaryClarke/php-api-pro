<?php

declare(strict_types=1);

namespace App\Http\Error\Exception;

use Slim\Exception\HttpSpecializedException;

class ValidationException extends HttpSpecializedException implements ExtensibleExceptionInterface
{
    protected array $errors = [];

    protected $code = 422;

    protected $message = 'Unprocessable Content.';

    protected string $title = '422 Unprocessable Content';
    protected string $description = 'The request could not be processed by this server.';

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setErrors(array $errors): self
    {
        $this->errors = $errors;
        return $this;
    }

    public function getExtensions(): array
    {
        return ['errors' => $this->errors];
    }
}
