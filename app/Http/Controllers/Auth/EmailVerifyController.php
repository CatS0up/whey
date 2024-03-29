<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\VerifyEmailAction;
use App\Enums\SweetAlertToastType;
use App\Http\Controllers\Controller;
use App\Models\EmailVerify;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;

class EmailVerifyController extends Controller
{
    public function __invoke(EmailVerify $token, VerifyEmailAction $action): RedirectResponse
    {
        $action->execute($token->id);

        // TODO: Tłumaczenia
        return to_route(RouteServiceProvider::GUEST_USER_HOME)
            ->with(
                key: SweetAlertToastType::Success->type(),
                // TODO: Tłumaczenia
                value: 'Your email has been verified :)',
            );
    }
}
