<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\SweetAlertToastType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfHasTemporaryPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            if(auth()->user()->hasTemporaryPassword()) {
                // TODO: Tłumaczenia
                return to_route('auth.resetPassword.show')
                    ->with(
                        key: SweetAlertToastType::Warning->type(),
                        value: 'You have a temporary password. If you want to continue, you should set a new password',
                    );
            }
        }

        return $next($request);
    }
}
