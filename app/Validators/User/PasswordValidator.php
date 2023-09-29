<?php

declare(strict_types=1);

namespace App\Validators\User;

use App\Models\User;
use Illuminate\Validation\Rules\Password;

class PasswordValidator
{
    public function rules(): array
    {
        return [
            'required',
            'string',
            'confirmed',
            Password::min(User::MIN_PASSWORD_LENGTH)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(),
        ];
    }
}
