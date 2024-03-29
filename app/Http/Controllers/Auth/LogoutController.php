<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LogoutAction;
use App\Enums\SweetAlertToastType;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;

class LogoutController extends Controller
{
    public function __invoke(LogoutAction $action): RedirectResponse
    {
        if ($action->execute()) {
            // TODO: Tłumaczenia
            return to_route(RouteServiceProvider::GUEST_USER_HOME)
                ->with(
                    key: SweetAlertToastType::Success->type(),
                    value: 'You are logged out',
                );
        }

        // TODO: Tłumaczenia
        return back()
            ->with(
                key: SweetAlertToastType::Error->type(),
                value: 'Logout failed',
            );
    }
}
