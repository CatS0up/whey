<?php

declare(strict_types=1);

namespace Tests\Concerns;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\PermissionRoleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Gate;

trait Authorization
{
    protected function runPermissionSeeders(): void
    {
        $this->seed([
            RoleSeeder::class,
            PermissionSeeder::class,
            PermissionRoleSeeder::class,
        ]);
    }

    protected function assignPermissionToUser(User $user, string $name): void
    {
        $permission = Permission::query()
            ->whereName($name)
            ->orWhere('slug', $name)
            ->firstOr(fn (): Permission => Permission::factory()->create(['name' => $name]));

        $user->permissions()->save($permission);

        Gate::define(
            ability: $permission->slug,
            callback: fn (): bool => $user->hasPermissionToBySlug($permission->slug),
        );
    }

    protected function assignRoleToUser(User $user, string $name): void
    {
        $permission = Role::query()
            ->whereName($name)
            ->orWhere('slug', $name)
            ->firstOr(fn (): Role => Role::factory()->create(['name' => $name]));

        $user->roles()->save($permission);
    }
}
