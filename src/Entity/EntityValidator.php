<?php

declare(strict_types=1);

namespace App\Entity;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EntityValidator
{
    public function __construct(
        private ValidatorInterface $validator
    ) {
    }

    public function validate(EntityInterface $entity, ServerRequestInterface $request)
    {
        // Get errors array off validate()
        $errors = $this->validator->validate($entity);

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

        dd($validationErrors);

        // Create a ValidationException

        // Add errors to the ValidationException

        // Throw the exception
    }
}



