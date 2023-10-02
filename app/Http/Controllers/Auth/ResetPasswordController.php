<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\ResetTemporaryPasswordAction;
use App\Enums\SweetAlertToastType;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    public function show(): View
    {
        return view('auth.sections.reset-password');
    }

    public function reset(UpdatePasswordRequest $request, ResetTemporaryPasswordAction $action): RedirectResponse
    {
        if ($action->execute($request->toDataObject())) {
            // TODO: Tłumaczenia
            return to_route('auth.login.show')
                ->with(
                    key: SweetAlertToastType::Success->type(),
                    value: 'Your password has been reset. You can sign up now :)',
                );
        }

        return to_route('auth.login.show')
            ->with(
                key: SweetAlertToastType::Error->type(),
                value: 'An unknown error occurred during the password reset',
            );
    }
}
