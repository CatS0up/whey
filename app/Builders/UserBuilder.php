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
    /** Model methods - start */
    public function markPasswordAsTemporary(): void
    {
        $this->model->has_temporary_password = User::HAS_TEMPORARY_PASSWORD;
        $this->model->save();
    }

    public function unmarkPasswordAsTemporary(): void
    {
        // @phpstan-ignore-next-line
        $this->model->has_temporary_password = ! User::HAS_TEMPORARY_PASSWORD;
        $this->model->save();
    }

    public function hasTemporaryPassword(): bool
    {
        return $this->model->has_temporary_password;
    }

    public function hasNotTemporaryPassword(): bool
    {
        return ! $this->model->hasTemporaryPassword();
    }

    public function hasVerifiedEmail(): bool
    {
        return ! empty($this->model->email_verified_at);
    }

    public function verifyEmail(): void
    {
        $this->model->email_verified_at = now();
        $this->model->save();
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

    public function hasAnyPermissionToBySlug(string ...$slugs): bool
    {
        if ($this->model->permissions->whereIn('slug', $slugs)->isNotEmpty()) {
            return true;
        }

        $permissions = $this->model->roles->pluck('permissions')->collapse();
        return (bool) ($permissions->whereIn('slug', $slugs)->isNotEmpty());
    }

    public function hasRoleBySlug(string ...$slugs): bool
    {
        return $this->model->roles->contains('slug', ...$slugs);
    }

    public function hasAnyRoleBySlug(string ...$slugs): bool
    {
        return $this->model->roles->whereIn('slug', $slugs)->isNotEmpty();
    }
    /** Model methods - end */

    /** Builder methods - start */
    public function firstOrFailByEmail(string $email): User
    {
        return $this->whereEmail($email)->firstOrFail();
    }

    public function whereRole(string ...$slugs): self
    {
        return $this->whereHas(
            relation: 'roles',
            callback: fn (RoleBuilder $q) => $q->whereSlugIn($slugs),
            operator: '=',
            count: count($slugs),
        );
    }

    public function wherePermissionTo(string ...$slugs): self
    {
        return $this->whereHas(
            relation: 'permissions',
            callback: fn (PermissionBuilder $q) => $q->whereSlugIn($slugs),
            operator: '=',
            count: count($slugs),
        )
            ->orWhereHas(
                relation: 'roles.permissions',
                callback: fn (PermissionBuilder $q) => $q->whereSlugIn($slugs),
                operator: '=',
                count: count($slugs),
            );
    }
    /** Builder methods - end */
}
