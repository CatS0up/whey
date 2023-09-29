<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Exceptions\Auth\EmailVerifyTokenExpired;
use App\Exceptions\Auth\UserHasVerifiedEmail;
use App\Models\EmailVerify;
use App\Models\User;

class VerifyEmailAction
{
    public function __construct(
        private EmailVerify $emailVerify,
    ) {
    }

    public function execute(int $id): EmailVerify
    {
        /** @var EmailVerify $token */
        $token = $this->emailVerify->query()->findOrFail($id);
        /** @var User $user */
        $user = $token->user;

        if ($token->isExpired()) {
            throw EmailVerifyTokenExpired::because('Email verification token expired');
        }

        if ($user->hasVerifiedEmail()) {
            throw UserHasVerifiedEmail::because('Given user has verified email');
        }

        return tap($token, fn () => $token->user->verifyEmail());
    }
}
