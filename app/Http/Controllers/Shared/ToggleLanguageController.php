<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shared;

use App\Actions\Shared\ToggleLanguageAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ToggleLanguageController extends Controller
{
    public function __invoke(ToggleLanguageAction $toggleLanguageAction, string $locale): RedirectResponse
    {
        $toggleLanguageAction->execute($locale);

        return back();
    }
}
