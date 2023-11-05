<?php

declare(strict_types=1);

namespace Tests\Concerns;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

trait Authorization
{
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
}
