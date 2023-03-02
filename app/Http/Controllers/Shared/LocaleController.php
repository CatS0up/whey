<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shared;

use App\Actions\Shared\SwitchLocaleAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    public function __invoke(SwitchLocaleAction $switchLocaleAction, string $locale): RedirectResponse
    {
        $switchLocaleAction->execute($locale);

        return back();
    }
}
