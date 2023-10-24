<?php

declare(strict_types=1);

namespace App\Observers;

use App\Actions\Shared\CalculateBmiAction;
use App\Enums\Role;
use App\Models\User;
use App\Services\User\RoleService;
use Illuminate\Auth\AuthManager;

class UserObserver
{
    public function __construct(
        private RoleService $roleService,
        private CalculateBmiAction $calculateBmiAction,
        private AuthManager $auth,
    ) {
    }

    public function saving(User $user): void
    {
        $this->upsertBmiField($user);
        $this->refreshUserWhenIsAuth($user);
    }

    public function saved(User $user): void
    {
        $this->assingDefaultRole($user);
    }

    private function upsertBmiField(User $user): void
    {
        if (($user->isDirty('weight') && $user->weight) || ($user->isDirty('height') && $user->height)) {
            $user->bmi = $this->calculateBmiAction->execute($user->weight, $user->height);
        }
    }

    private function assingDefaultRole(User $user): void
    {
        if ($user->roles->isEmpty()) {
            $this->roleService->giveRoles($user->id, [Role::User->value]);
        }
    }

    private function refreshUserWhenIsAuth(User $user): void
    {
        if ($this->auth->check()) {
            if ($this->auth->id() === $user->id) {
                $this->auth->user()->refresh();
            }
        }
    }
}
