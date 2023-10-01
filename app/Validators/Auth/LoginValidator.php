<?php

declare(strict_types=1);

namespace App\Validators\Auth;

class LoginValidator
{
    public function rules(): array
    {
        return [
            'login' => [
                'required',
            ],
            'password' => [
                'required',
            ],
            'remember_me' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
