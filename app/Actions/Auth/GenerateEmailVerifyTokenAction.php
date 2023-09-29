<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Exceptions\Auth\UserHasEmailVeirfyActiveToken;
use App\Exceptions\Auth\UserHasVerifiedEmail;
use App\Models\EmailVerify;
use App\Models\User;

class GenerateEmailVerifyTokenAction
{
    public function __construct(
        private User $user,
        private EmailVerify $emailVerify,
    ) {
    }

    public function execute(int $userId): EmailVerify
    {
        $user = $this->user->query()->findOrFail($userId);
        $userHasActiveToken = $this->emailVerify->query()
            ->whereIsActiveForUser($userId)
            ->exists();

        if ($user->hasVerifiedEmail()) {
            throw UserHasVerifiedEmail::because('Given user has verified email');
        }

        if ($userHasActiveToken) {
            throw UserHasEmailVeirfyActiveToken::because('Given user has active token');
        }

        return $this->emailVerify->create([
            'user_id' => $userId,
        ]);
    }
}
