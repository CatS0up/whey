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

    public function hasPermissionToBySlug(string ...$slugs): bool
    {
        if ($this->model->permissions->contains('slug', ...$slugs)) {
            return true;
        }

        $roles = $this->model->roles;
        foreach ($roles as $role) {
            if ($role->permissions->contains('slug', ...$slugs)) {
                return true;
            }
        }

        return false;
    }

    public function hasRoleBySlug(string ...$slugs): bool
    {
        return $this->model->roles->contains('slug', ...$slugs);
    }
}
