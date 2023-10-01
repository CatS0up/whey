<?php

declare(strict_types=1);

namespace Tests\Concerns;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

trait Authentication
{
    protected User $user;

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
            $this->user = $this->createUser();
        });
    }

    protected function createUser(): User
    {
        return User::factory()->create();
    }

    protected function refreshUser(): void
    {
        $this->user = $this->user->fresh();
    }
}
