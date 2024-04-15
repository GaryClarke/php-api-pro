<?php // src/Serializer/Serializer.php

declare(strict_types=1);

namespace App\Serializer;

use Symfony\Component\Serializer\SerializerInterface;

class Serializer implements SerializerInterface
{
    private string $format = 'json';

    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function serialize(mixed $data, ?string $format = null, array $context = []): string
    {
        return $this->serializer->serialize($data, $format ?? $this->format, $context);
    }

    public function deserialize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        return $this->serializer->deserialize($data ,$type, $format ?? $this->format, $context);
    }

    public function setFormat(string $format): void
    {
        $this->format = $format;
    }
}