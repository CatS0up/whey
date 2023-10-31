<?php

declare(strict_types=1);

namespace App\Validators\Media;

use Illuminate\Validation\Rules\File;

class CKEditorImageValidator
{
    public function rules(): array
    {
        return [
            'upload' => [
                'image',
                File::types(['png', 'jpg', 'gif'])
                    ->max(5 * 1024),
            ],
        ];
    }
}
