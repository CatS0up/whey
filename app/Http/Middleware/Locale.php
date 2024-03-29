<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Locale
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($locale = session()->get('locale')) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
