<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Actions\User\UpdateUserPasswordAction;
use App\DataObjects\User\UpdatePasswordData;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GenerateTemporaryPasswordAction
{
    public function __construct(
        private UpdateUserPasswordAction $updateUserPasswordAction,
        private User $user,
    ) {
    }

    public function execute(int $userId): string
    {
        /** @var User $user */
        $user = $this->user->query()->findOrFail($userId);
        $tempPassword = str()->random();

        return DB::transaction(function () use ($user, $tempPassword): string {
            $user->markPasswordAsTemporary();

            $this->updateUserPasswordAction->execute(UpdatePasswordData::from([
                'user_id' => $user->id,
                'password' => $tempPassword,
            ]));

            return $tempPassword;
        });
    }
}
