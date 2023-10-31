<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    public function __invoke(AuthManager $auth): RedirectResponse
    {
        if ($auth->check()) {
            return to_route(RouteServiceProvider::AUTH_USER_HOME);
        }

        return to_route(RouteServiceProvider::GUEST_USER_HOME);
    }
}
