<?php

declare(strict_types=1);

namespace App\Actions\Shared;

class SwitchLocaleAction
{
    public function execute(string $locale): string
    {
        if ($this->isSupportedLocale($locale)) {
            session()->put('locale', $locale);
            app()->setLocale($locale);
        }

        return app()->getLocale();
    }

    private function isSupportedLocale(string $locale): bool
    {
        return in_array($locale, config('app.available_locales'));
    }
}
