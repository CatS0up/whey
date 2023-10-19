<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Models\Permission;
use App\Models\User;

class PermissionService
{
    public function __construct(
        private User $user,
        private Permission $permission,
    ) {
    }

    public function givePermissionsTo(int $userId, array $slugs): void
    {
        $ids = $this->getPermissionIdsBySlugs($slugs);
        $user = $this->findUser($userId);

        $user->permissions()->syncWithoutDetaching($ids);
    }

    public function withdrawPermissionsTo(int $userId, array $slugs): void
    {
        $ids = $this->getPermissionIdsBySlugs($slugs);
        $user = $this->findUser($userId);

        $user->permissions()->detach($ids);
    }

    public function refreshPermissions(int $userId, array $slugs): void
    {
        $ids = $this->getPermissionIdsBySlugs($slugs);
        $user = $this->findUser($userId);

        $user->permissions()->sync($ids);
    }

    private function findUser(int $id): User
    {
        return $this->user->query()
            ->findOrFail($id);
    }

    private function getPermissionIdsBySlugs(array $slugs): array
    {
        return $this->permission->query()
            ->whereSlugIn($slugs)
            ->pluck('id')
            ->toArray();
    }
}
