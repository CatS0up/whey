<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\SendTemporaryPasswordAction;
use App\Enums\SweetAlertToastType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SendTemporaryPasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SendTemporaryPasswordController extends Controller
{
    public function show(): View
    {
        return view('auth.sections.send-password-reset-link');
    }

    public function send(
        User $user,
        SendTemporaryPasswordRequest $request,
        SendTemporaryPasswordAction $action,
    ): RedirectResponse {
        $action->execute(
            userId: $user->query()
                ->firstOrFailByEmail(
                    email: $request->email,
                )
                ->value('id'),
        );

        // TODO: Tłumaczenia
        return to_route('auth.login.show')
            ->with(
                key: SweetAlertToastType::Success->type(),
                value: 'Temporary password has been sent',
            );
    }
}
