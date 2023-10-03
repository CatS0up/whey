<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = Http::asForm()->post(
            url: 'https://www.google.com/recaptcha/api/siteverify',
            data: [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $value,
                'ip' => request()->ip(),
            ],
        );

        if ( ! ($response->successful() && $response->json('success') && $response->json('score') > config('services.recaptcha.min_score'))) {
            // TODO: Tłumaczenia
            $fail('Failed to validate ReCaptcha');
        }
    }
}
