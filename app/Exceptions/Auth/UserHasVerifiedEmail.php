<?php

declare(strict_types=1);

namespace App\Exceptions\Auth;

use App\Enums\SweetAlertToastType;
use App\Exceptions\Contracts\Renderable;
use App\Exceptions\Exception;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class UserHasVerifiedEmail extends Exception implements Renderable
{
    public function render(Request $request): RedirectResponse
    {
        // TODO: Tłumaczenia
        return to_route(
            route: 'auth.login.show',
        )
            ->with(
                key: SweetAlertToastType::Info->type(),
                value: 'User has verified email',
            );
    }
}
