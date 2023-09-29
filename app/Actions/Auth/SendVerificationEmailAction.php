<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use App\Notifications\Auth\EmailVerificationNotification;

class SendVerificationEmailAction
{
    public function __construct(
        private GenerateEmailVerifyTokenAction $generateEmailVerifyTokenAction,
        private User $user,
    ) {
    }

    public function execute(int $userId): bool
    {
        /** @var User $user */
        $user = $this->user->query()->findOrFail($userId);
        $token = $this->generateEmailVerifyTokenAction->execute($user->id);

        $user->notify(new EmailVerificationNotification($token));

        return $user->emailVerifyToken()->exists();
    }
}
