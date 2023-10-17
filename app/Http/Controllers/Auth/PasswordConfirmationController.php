<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\ConfirmPasswordAction;
use App\Enums\SweetAlertToastType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordConfirmationRequest;
use Illuminate\Session\SessionManager;
use Illuminate\View\View;

class PasswordConfirmationController extends Controller
{
    public function show(): View
    {
        return view('auth.sections.confirm-password');
    }

    /**
     * Confirm the user's password.
     */
    public function confirm(
        PasswordConfirmationRequest $request,
        SessionManager $session,
        ConfirmPasswordAction $action,
    ) {
        $redirectToUrl = $session->get('password_confirmation_destination_path');

        if ($action->execute($request->toDataObject())) {
            $session->forget('password_confirmation_destination_path');
            return redirect($redirectToUrl);
        }

        // TODO: Tłumaczenia
        return to_route('auth.confirmPassword.show')
            ->with(
                key: SweetAlertToastType::Error->type(),
                value: 'The provided password does not match our records',
            );
    }
}
