<?php

declare(strict_types=1);

namespace App\Exceptions\Exercise;

use App\Enums\SweetAlertToastType;
use App\Exceptions\Contracts\Renderable;
use App\Exceptions\Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExerciseHasBeenRejected extends Exception implements Renderable
{
    public function render(Request $request): RedirectResponse|false
    {
        if ($request->routeIs('web.exercises.verification.show', 'web.exercises.verification.request')) {
            // TODO: Tłumaczenia
            return to_route('web.exercises.index')
                ->with(
                    key: SweetAlertToastType::Info->type(),
                    value: 'The given exercise has already been rejected earlier',
                );
        }

        return false;
    }
}
