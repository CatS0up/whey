<?php

declare(strict_types=1);

namespace App\Validators\Auth;

class PasswordConfirmationValidator
{
    public function rules(): array
    {
        return [
            'password' => [
                'required',
            ],
        ];
    }
}
