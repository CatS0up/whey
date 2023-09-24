<?php

declare(strict_types=1);

namespace App\Observers;

use App\Actions\Shared\CalculateBmiAction;
use App\Models\User;

class UserObserver
{
    public function __construct(
        private CalculateBmiAction $calculateBmiAction,
    ) {
    }

    public function saving(User $user): void
    {
        $this->upsertBmiField($user);
    }

    private function upsertBmiField(User $user): void
    {
        if (($user->isDirty('weight') && $user->weight) || ($user->isDirty('height') && $user->height)) {
            $user->bmi = $this->calculateBmiAction->execute($user->weight, $user->height);
        }
    }
}
