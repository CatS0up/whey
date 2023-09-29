<?php

declare(strict_types=1);

namespace App\Exceptions\Auth;

use App\Enums\SweetAlertToastType;
use App\Exceptions\Contracts\Renderable;
use App\Exceptions\Exception;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class EmailVerifyTokenExpired extends Exception implements Renderable
{
    public function render(Request $request): RedirectResponse|false
    {
        if ($request->route()->hasParameter('token')) {
            // TODO: Tłumaczenia
            return to_route(
                route: 'auth.emailVerify.resend.show',
                parameters: [
                    'token' => $request->route('token'),
                ],
            )
                ->with(
                    key: SweetAlertToastType::Warning->type(),
                    value: 'Given token has been expired',
                );
        }

        return false;
    }
}
