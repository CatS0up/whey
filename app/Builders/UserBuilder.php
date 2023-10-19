<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of \App\Models\User
 * @extends Builder<TModelClass>
 *
 * @property User $model
 */
class UserBuilder extends Builder
{
    public function firstOrFailByEmail(string $email): User
    {
        return $this->whereEmail($email)->firstOrFail();
    }

    public function hasPermissionToBySlug(string $slug): bool
    {
        $roles = $this->model->roles;

        if ($roles->contains('slug', $slug)) {
            return true;
        }

        foreach ($roles as $role) {
            if ($role->permissions->contains('slug', $slug)) {
                return true;
            }
        }

        return false;
    }

    public function hasRoleBySlug(string $slug): bool
    {
        return $this->model->roles->contains('slug', $slug);
    }
}
