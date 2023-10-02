<?php

declare(strict_types=1);

namespace App\Validators\User;

class EmailForSendValidator
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'exists:App\Models\User,email',
            ],
        ];
    }
}
