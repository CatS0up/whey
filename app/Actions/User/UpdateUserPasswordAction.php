<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;

class UpdateUserPasswordAction
{
    public function __construct(private User $user)
    {
    }

    public function execute(int $userId, string $newPassword): bool
    {
        return $this->user->query()
            ->findOrFail($userId)
            ->update([
                'password' => $newPassword,
            ]);
    }
}
