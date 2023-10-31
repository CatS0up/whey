<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\SendVerificationEmailAction;
use App\Enums\SweetAlertToastType;
use App\Http\Controllers\Controller;
use App\Models\EmailVerify;
use App\Providers\RouteServiceProvider;
use App\ViewModels\Auth\GetEmailVerifyResendViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmailVerifyResendController extends Controller
{
    public function show(EmailVerify $token): View
    {
        return view(
            view: 'auth.sections.email-verify-resend',
            data: [
                'model' => (new GetEmailVerifyResendViewModel($token))->toArray(),
            ],
        );
    }

    public function resend(EmailVerify $token, SendVerificationEmailAction $action): RedirectResponse
    {
        $action->execute($token->user->id);

        return to_route(RouteServiceProvider::GUEST_USER_HOME)
            ->with(
                key: SweetAlertToastType::Success->type(),
                value: 'The verification link has been sent',
            );
    }
}
