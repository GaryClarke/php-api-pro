<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\EntityValidator;
use App\Serializer\Serializer;
use Doctrine\ORM\EntityManagerInterface;

readonly abstract class ApiController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected Serializer $serializer,
        protected EntityValidator $validator
    ) {
    }
}