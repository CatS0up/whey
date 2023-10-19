<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Models\Role;
use App\Models\User;

class RoleService
{
    public function __construct(
        private User $user,
        private Role $role,
    ) {
    }

    public function giveRoles(int $userId, array $slugs): void
    {
        $ids = $this->getRolesIdsBySlugs($slugs);
        $user = $this->findUser($userId);

        $user->roles()->syncWithoutDetaching($ids);
    }

    public function withdrawRoles(int $userId, array $slugs): void
    {
        $ids = $this->getRolesIdsBySlugs($slugs);
        $user = $this->findUser($userId);

        $user->roles()->detach($ids);
    }

    public function refreshRoles(int $userId, array $slugs): void
    {
        $ids = $this->getRolesIdsBySlugs($slugs);
        $user = $this->findUser($userId);

        $user->roles()->sync($ids);
    }

    private function findUser(int $id): User
    {
        return $this->user->query()
            ->findOrFail($id);
    }

    private function getRolesIdsBySlugs(array $slugs): array
    {
        return $this->role->query()
            ->whereSlugIn($slugs)
            ->pluck('id')
            ->toArray();
    }
}
