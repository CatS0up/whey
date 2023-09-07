<?php

declare(strict_types=1);

namespace Tests\Concerns;

use App\Models\User;

trait Authentication
{
    protected User $user;

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
}
