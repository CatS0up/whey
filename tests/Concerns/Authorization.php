<?php

declare(strict_types=1);

namespace Tests\Concerns;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

trait Authorization
{
    protected function assignPermissionToUser(User $user, Permission $permission): void
    {
        $user->permissions()->save($permission);

        Gate::define(
            ability: $permission->slug,
            callback: fn (): bool => $user->hasPermissionToBySlug($permission->slug),
        );
    }
}
