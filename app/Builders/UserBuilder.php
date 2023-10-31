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

    public function hasRoleBySlug(string ...$slugs): bool
    {
        return $this->model->roles->contains('slug', ...$slugs);
    }
    /** Model methods - end */

    /** Builder methods - start */
    public function firstOrFailByEmail(string $email): User
    {
        return $this->whereEmail($email)->firstOrFail();
    }
    /** Builder methods - end */
}
