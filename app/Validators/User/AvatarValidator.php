<?php

declare(strict_types=1);

namespace App\Validators\User;

use Illuminate\Validation\Rules\File;

class AvatarValidator
{
    public function rules(): array
    {
        return [
            'avatar' => [
                'nullable',
                'image',
                File::types(['png', 'jpg', 'gif'])
                    ->max(5 * 1024),
            ],
        ];
    }
}
