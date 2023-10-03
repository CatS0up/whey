<?php

declare(strict_types=1);

namespace Tests\Concerns;

use Illuminate\Support\Facades\Http;

trait SkipReCaptchaValidation
{
    protected function skipReCaptchaValidation(): void
    {
        $this->afterApplicationCreated(function (): void {
            /**
             * Skip captcha validation because it is dependent for http request
             * @see \App\Rules\Recaptcha::validate()
             */
            Http::preventStrayRequests();
            Http::fake([
                'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                    'success' => true,
                    'score' => 9999,
                ]),
            ]);
        });
    }
}
