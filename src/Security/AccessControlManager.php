<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\ResourceInterface;
use App\Entity\User;

class AccessControlManager
{
    // roles
    public const ROLE_ADMIN = 'admin';
    public const ROLE_PARTNER = 'partner';

    // permissions
    public const UPDATE_RESERVATION = 'UPDATE_RESERVATION';
    public const CREATE_RESERVATION = 'CREATE_RESERVATION';

    public array $rolePermissions = [
        self::ROLE_ADMIN => [],
        self::ROLE_PARTNER => [self::UPDATE_RESERVATION, self::CREATE_RESERVATION]
    ];

    public function hasPermission(string $role, string $permission): bool
    {
        return in_array($permission, $this->rolePermissions[$role]);
    }


    public function can(string $permission, User $user, ResourceInterface $resource): bool
    {
        // Is an admin
        if ($user->getRole() === self::ROLE_ADMIN) {
            return true;
        }

        // Has permission + is creator of the resource
        return $this->hasPermission($user->getRole(), $permission)
            && $user->getId() === $resource->getOwnerId();
    }
}