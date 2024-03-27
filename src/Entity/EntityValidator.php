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
        dd('validate method!');
    }
}



