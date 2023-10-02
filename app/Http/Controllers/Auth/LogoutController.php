<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LogoutAction;
use App\Enums\SweetAlertToastType;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class LogoutController extends Controller
{
    public function __invoke(LogoutAction $action): RedirectResponse
    {
        if ($action->execute()) {
            // TODO: Tłumaczenia
            return to_route('auth.login.show')
                ->with(
                    key: SweetAlertToastType::Success->type(),
                    value: 'You are logged out',
                );
        }

        // TODO: Tłumaczenia
        return to_route('auth.login.show')
            ->with(
                key: SweetAlertToastType::Error->type(),
                value: 'Logout failed',
            );
    }
}
