<?php

declare(strict_types=1);

namespace App\Exceptions\Auth;

use App\Enums\SweetAlertToastType;
use App\Exceptions\Contracts\Renderable;
use App\Exceptions\Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserHasEmailVeirfyActiveToken extends Exception implements Renderable
{
    public function render(Request $request): RedirectResponse
    {
        // TODO: Tłumacznie
        return to_route('auth.login.show')
            ->with(
                key: SweetAlertToastType::Info->type(),
                value: 'User has active email verification token',
            );
    }
}
