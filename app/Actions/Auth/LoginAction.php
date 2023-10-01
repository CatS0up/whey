<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\DataObjects\Auth\LoginData;
use Illuminate\Auth\AuthManager;

class LoginAction
{
    public function __construct(
        private AuthManager $auth,
    ) {
    }

    public function execute(LoginData $data): bool
    {
        return $this->auth->attempt(
            credentials: $data->toCredentials(),
            remember: $data->remember_me,
        );
    }
}
