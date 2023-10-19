<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Builders\UserBuilder;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class LoginAsDemoUserAction
{
    public function __construct(
        private User $user,
        private AuthManager $auth,
    ) {
    }

    public function execute(Role $role): bool
    {
        if ( ! config('auth.demo_users_enable')) {
            throw new RuntimeException('Demo users are disabled');
        }

        $user = $this->findOrFailUserByRole($role);

        $this->auth->loginUsingId($user->id);

        return $this->auth->check() && $this->auth->id() === $user->id;
    }

    /** @return Model&User */
    private function findOrFailUserByRole(Role $role): User
    {
        return $this->user->query()
            ->when(
                value: Role::User === $role,
                callback: fn (Builder $q): UserBuilder => $q->whereName('user'),
            )
            ->when(
                value: Role::Trainer === $role,
                callback: fn (Builder $q): UserBuilder => $q->whereName('trainer'),
            )
            ->when(
                value: Role::Admin === $role,
                callback: fn (Builder $q): UserBuilder => $q->whereName('admin'),
            )
            ->firstOrFail();
    }
}
