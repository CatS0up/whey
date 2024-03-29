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
        abort_if(
            boolean: auth()->guest(),
            code: Response::HTTP_UNAUTHORIZED,
        );

        if(auth()->user()->hasTemporaryPassword()) {
            // TODO: Tłumaczenia
            return to_route('auth.temporaryPassword.reset.show')
                ->with(
                    key: SweetAlertToastType::Warning->type(),
                    value: 'You have a temporary password. If you want to continue, you should set a new password',
                );
        }

        return $next($request);
    }
}
