<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserBuilder extends Builder
{
    public function firstOrFailByEmail(string $email): User
    {
        return $this->whereEmail($email)->firstOrFail();
    }

    public function wherePhoneNumber(string $phoneNumber): self
    {
        return $this->where(function (Builder $query) use ($phoneNumber): void {
            $query->where('phone_normalized', 'LIKE', preg_replace('[^0-9]', '', $phoneNumber).'%')
                ->orWhere('phone_national', 'LIKE', preg_replace('[^0-9]', '', $phoneNumber).'%')
                ->orWhere('phone_e164', 'LIKE', preg_replace('[^+0-9]', '', $phoneNumber).'%');
        });
    }
}
