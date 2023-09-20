<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function creating(User $user): void
    {
        $this->upsertPhoneFields($user);
    }

    public function updating(User $user): void
    {
        $this->upsertPhoneFields($user);
    }

    private function upsertPhoneFields(User $user): void
    {
        if ($user->isDirty('phone') && $user->phone) {
            $number = (string) $user->phone;
            $user->phone_normalized = preg_replace('[^0-9]', '', $number);
            $user->phone_national = preg_replace('[^0-9]', '', phone($number, $user->phone_country)->formatNational());
            $user->phone_e164 = phone($number, $user->phone_country)->formatE164();
            $user->phone_country = $user->phone->getCountry();
        }
    }
}
