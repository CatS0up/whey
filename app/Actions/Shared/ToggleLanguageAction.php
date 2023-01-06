<?php

declare(strict_types=1);

namespace App\Actions\Shared;

class ToggleLanguageAction
{
    public function execute(string $locale): string
    {
        if (in_array($locale, config('app.available_locales'))) {
            app()->setLocale($locale);
            session(['locale' => $locale]);
        }

        return app()->getLocale();
    }
}
