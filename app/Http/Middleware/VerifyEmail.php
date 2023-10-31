<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\SweetAlertToastType;
use App\Providers\RouteServiceProvider;
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
        abort_if(
            boolean: auth()->guest(),
            code: Response::HTTP_UNAUTHORIZED,
        );

        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if ( ! auth($guard)->user()->hasVerifiedEmail()) {
                auth($guard)->logout();

                // TODO: Tłumaczenie
                return to_route(RouteServiceProvider::GUEST_USER_HOME)
                    ->with(
                        key: SweetAlertToastType::Info->type(),
                        value: 'You need to confirm your account. We have sent you an activation link, please check your email.',
                    );
            }
        }
        return $next($request);
    }
}
