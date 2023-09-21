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
            $number = (string) $user->phone;
            $country = $user->phone->getCountry();
            $user->phone_normalized = preg_replace('[^0-9]', '', $number);
            $user->phone_national = preg_replace('[^0-9]', '', phone($number, $country)->formatNational());
            $user->phone_e164 = phone($number, $country)->formatE164();
            $user->phone_country = $country;
        }
    }

    private function upsertBmiField(User $user): void
    {
        if (($user->isDirty('weight') && $user->weight) || ($user->isDirty('height') && $user->height)) {
            $user->bmi = $this->calculateBmiAction->execute($user->weight, $user->height);
        }
    }
}
