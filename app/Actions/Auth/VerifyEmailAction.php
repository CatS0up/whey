<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Exceptions\Auth\EmailVerifyTokenExpired;
use App\Exceptions\Auth\UserHasVerifiedEmail;
use App\Models\EmailVerify;

class VerifyEmailAction
{
    public function __construct(
        private EmailVerify $emailVerify,
    ) {
    }

    public function execute(int $id): EmailVerify
    {
        $token = $this->emailVerify->query()->findOrFail($id);

        if ($token->isExpired()) {
            throw EmailVerifyTokenExpired::because('Email verification token expired');
        }

        if ($token->user->hasVerifiedEmail()) {
            throw UserHasVerifiedEmail::because('Given user has verified email');
        }

        return tap($token, fn () => $token->user->verifyEmail());
    }
}
