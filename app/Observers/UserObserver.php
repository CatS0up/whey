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

    public function creating(User $user): void
    {
        $this->upsertPhoneFields($user);
        $this->upsertBmiField($user);
    }

    public function updating(User $user): void
    {
        $this->upsertPhoneFields($user);
        $this->upsertBmiField($user);
    }

    private function upsertPhoneFields(User $user): void
    {
        if ($user->isDirty('phone') && $user->phone) {
            $user->phone_normalized = preg_replace('[^0-9]', '', (string) $user->phone);
            $user->phone_national = preg_replace('[^0-9]', '', $user->phone->formatNational());
            $user->phone_e164 = $user->phone->formatE164();
            $user->phone_country = $user->phone->getCountry();
        }
    }

    private function upsertBmiField(User $user): void
    {
        if (($user->isDirty('weight') && $user->weight) || ($user->isDirty('height') && $user->height)) {
            $user->bmi = $this->calculateBmiAction->execute($user->weight, $user->height);
        }
    }
}
