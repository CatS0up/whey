<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Illuminate\Auth\AuthManager;
use Illuminate\Session\SessionManager;

class LogoutAction
{
    /** @var bool */
    private const IS_LOGGED_OUT = true;

    public function __construct(
        private SessionManager $session,
        private AuthManager $auth,
    ) {
    }

    public function execute(): bool
    {
        if ($this->auth->check()) {
            $this->session->invalidate();
            $this->session->regenerateToken();

            $this->auth->logout();

            return self::IS_LOGGED_OUT;
        }

        return !self::IS_LOGGED_OUT;
    }
}
