<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roleSlug): Response
    {
        abort_if(
            boolean: auth()->guest(),
            code: Response::HTTP_UNAUTHORIZED,
        );

        abort_if(
            boolean: ! auth()->user()->hasRoleBySlug($roleSlug),
            code: Response::HTTP_FORBIDDEN,
        );

        return $next($request);
    }
}
