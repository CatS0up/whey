<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\DataObjects\User\UpdatePasswordData;
use App\Models\User;

class UpdateUserPasswordAction
{
    public function __construct(private User $user)
    {
    }

    public function execute(UpdatePasswordData $data): bool
    {
        return $this->user->query()
            ->findOrFail($data->user_id)
            ->update([
                'password' => $data->password,
            ]);
    }
}
