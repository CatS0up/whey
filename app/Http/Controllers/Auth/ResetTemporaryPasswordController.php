<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\ResetTemporaryPasswordAction;
use App\Enums\SweetAlertToastType;
use App\Exceptions\Auth\UserHasNotTemporaryPassword;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ResetTemporaryPasswordController extends Controller
{
    public function show(AuthManager $auth): View
    {
        if ($auth->user()->hasNotTemporaryPassword()) {
            throw UserHasNotTemporaryPassword::because('Given user has no temporary password assigned');
        }

        return view('auth.sections.reset-temporary-password');
    }

    public function reset(UpdatePasswordRequest $request, ResetTemporaryPasswordAction $action): RedirectResponse
    {
        if ($action->execute($request->toDataObject())) {
            // TODO: Tłumaczenia
            return to_route(RouteServiceProvider::AUTH_USER_HOME)
                ->with(
                    key: SweetAlertToastType::Success->type(),
                    value: 'Your password has been reset. You can sign up now :)',
                );
        }

        return back()
            ->with(
                key: SweetAlertToastType::Error->type(),
                value: 'An unknown error occurred during the password reset',
            );
    }
}
