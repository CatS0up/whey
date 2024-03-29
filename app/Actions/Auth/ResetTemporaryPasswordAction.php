<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Actions\User\UpdateUserPasswordAction;
use App\DataObjects\User\UpdatePasswordData;
use App\Exceptions\Auth\UserHasNotTemporaryPassword;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ResetTemporaryPasswordAction
{
    public function __construct(
        private UpdateUserPasswordAction $updateUserPasswordAction,
        private User $user,
    ) {
    }

    public function execute(UpdatePasswordData $data): bool
    {
        /** @var User $user */
        $user = $this->user->query()->findOrFail($data->user_id);

        if ($user->hasNotTemporaryPassword()) {
            throw UserHasNotTemporaryPassword::because('Given user has no temporary password assigned');
        }

        return DB::transaction(function () use ($user, $data): bool {
            $user->unmarkPasswordAsTemporary();

            return $this->updateUserPasswordAction->execute($data);
        });
    }
}
