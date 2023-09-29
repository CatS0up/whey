<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\SweetAlertToastType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if ( ! auth($guard)->user()->hasVerifiedEmail()) {
                auth($guard)->logout();

                // TODO: Tłumaczenie
                return to_route('auth.login.show')
                    ->with(
                        key: SweetAlertToastType::Info->type(),
                        value: 'You need to confirm your account. We have sent you an activation link, please check your email.',
                    );
            }
        }
        return $next($request);
    }
}
