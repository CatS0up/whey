<?php

declare(strict_types=1);

namespace Tests\Concerns;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

trait Authentication
{
    protected User $user;

    public function getUserFactoryAttributes(): array
    {
        return [];
    }

    public function authenticated(Authenticatable $user = null): self
    {
        return $this->actingAs($user ?? $this->user);
    }

    public function markUserAsUnverified(): User
    {
        $this->user->email_verified_at = null;
        $this->user->save();

        return $this->user;
    }

    protected function setUpUser(): void
    {
        $this->afterApplicationCreated(function (): void {
            $attributes = $this->getUserFactoryAttributes();

            $this->user = $this->createUser($attributes);
        });
    }

    protected function createUser(array $attributes): User
    {
        return User::factory()->create($attributes);
    }

    protected function refreshUser(): void
    {
        $this->user = $this->user->fresh();
    }
}
