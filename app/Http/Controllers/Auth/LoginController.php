<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginAction;
use App\Enums\SweetAlertToastType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function show(): View
    {
        return view('auth.sections.login');
    }

    public function login(LoginRequest $loginRequest, LoginAction $action): RedirectResponse
    {
        $isAuth = $action->execute($loginRequest->toDataObject());

        if ( ! $isAuth) {
            // TODO: Tłumaczenia
            return to_route('auth.login.show')
                ->with(
                    key: SweetAlertToastType::Error->type(),
                    value: 'Invalid credentials',
                );
        }

        // TODO: Tłumaczenia
        return redirect()
            ->intended(route(RouteServiceProvider::HOME))
            ->with(
                key: SweetAlertToastType::Success->type(),
                value: 'Login has been succesful',
            );
    }
}
