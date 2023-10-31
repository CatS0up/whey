<?php

declare(strict_types=1);

namespace App\Exceptions\Auth;

use App\Enums\SweetAlertToastType;
use App\Exceptions\Contracts\Renderable;
use App\Exceptions\Exception;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class UserHasNotTemporaryPassword extends Exception implements Renderable
{
    public function render(Request $request): RedirectResponse
    {
        // TODO: Tłumaczenia
        return back()
            ->with(
                key: SweetAlertToastType::Error->type(),
                value: 'User has no temporary password assigned',
            );
    }
}
