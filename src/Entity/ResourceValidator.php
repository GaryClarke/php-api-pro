<?php

declare(strict_types=1);

namespace App\Entity;

use App\Http\Error\Exception\ValidationException;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ResourceValidator
{
    public function __construct(
        private ValidatorInterface $validator
    ) {
    }

    public function validate(ResourceInterface $entity, ServerRequestInterface $request, array $groups = []): void
    {
        // Get errors array off validate()
        $errors = $this->validator->validate(value: $entity, groups: $groups);

        // Return if no errors
        if (count($errors) === 0) {
            return;
        }

        // Initialize $validationErrors array
        $validationErrors = [];

        // Loop errors
        foreach ($errors as $error) {
            // Add property and message keys to $validationErrors
            $validationErrors[] = [
                'property' => $error->getPropertyPath(),
                'message' => $error->getMessage()
            ];
        }

        // Create a ValidationException
        $validationException = new ValidationException($request);

        // Add errors to the ValidationException
        $validationException->setErrors($validationErrors);

        // Throw the exception
        throw $validationException;
    }
}



