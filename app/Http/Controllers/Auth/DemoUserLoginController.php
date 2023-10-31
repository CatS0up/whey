<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginAsDemoUserAction;
use App\Enums\Role;
use App\Enums\SweetAlertToastType;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class DemoUserLoginController extends Controller
{
    public function show(): View
    {
        abort_if(
            boolean: ! config('auth.demo_users_enable'),
            code: Response::HTTP_NOT_FOUND,
        );

        return view('auth.sections.choose-role');
    }

    public function login(Role $role, LoginAsDemoUserAction $action): RedirectResponse
    {
        abort_if(
            boolean: ! config('auth.demo_users_enable'),
            code: Response::HTTP_NOT_FOUND,
        );

        if ( ! $action->execute($role)) {
            // TODO: Tłumaczenia
            return back()
                ->with(
                    key: SweetAlertToastType::Error->type(),
                    value: 'Unknown error',
                );
        }

        // TODO: Tłumaczenia
        return to_route(RouteServiceProvider::AUTH_USER_HOME)
            ->with(
                key: SweetAlertToastType::Success->type(),
                value: 'You are logged in as '.$role->value,
            );
    }
}
