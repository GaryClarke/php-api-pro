<?php // src/Entity/User.php

declare(strict_types=1);

namespace App\Entity;

readonly class User
{
    public function __construct(
        private string $id,
        private string $role
    ) {
    }

    public static function createFromToken(object $token): self
    {
        return new self($token->sub, $token->role ?? 'user');
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
