<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;
use Symfony\Component\HttpFoundation\Response;

class ConfirmPassword
{
    /** @var bool */
    private const PASSWORD_SHOULD_BE_CONFIRMED = true;

    public function __construct(
        private SessionManager $session,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        abort_if(
            boolean: auth()->guest(),
            code: Response::HTTP_UNAUTHORIZED,
        );

        if ($this->passwordShouldBeConfirmed()) {
            $this->session->put('password_confirmation_destination_path', $request->url());
            return to_route('auth.confirmPassword.show');
        }

        return $next($request);
    }

    private function passwordShouldBeConfirmed(): bool
    {
        $passwordConfirmedAt = $this->session->get('auth.password_confirmed_at');

        if ($passwordConfirmedAt) {
            return Carbon::createFromTimestamp($passwordConfirmedAt)
                ->addSeconds(config('auth.password_timeout'))
                ->lessThanOrEqualTo(now());
        }

        return self::PASSWORD_SHOULD_BE_CONFIRMED;
    }
}
