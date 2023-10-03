<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use App\Notifications\Auth\UserTemporaryPasswordNotification;

class SendTemporaryPasswordAction
{
    public function __construct(
        private GenerateTemporaryPasswordAction $generateTemporaryPasswordAction,
        private User $user,
    ) {
    }

    public function execute(int $userId): User
    {
        /** @var User $user */
        $user = $this->user->query()->findOrFail($userId);
        $tempPassword = $this->generateTemporaryPasswordAction->execute($userId);

        $user->notify(new UserTemporaryPasswordNotification($tempPassword));

        return $user;
    }
}
