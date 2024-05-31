<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\ResourceInterface;
use App\Entity\User;

class AccessControlManager
{
    public const ROLE_ADMIN = 'admin';

    public function can(string $permission, User $user, ResourceInterface $resource): bool
    {
        // Is an admin
        if ($user->getRole() === self::ROLE_ADMIN) {
            return true;
        }

        return false;

        // Has permission + is creator of the resource
    }
}