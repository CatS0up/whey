<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Actions\Media\UpsertAvatarAction;
use App\DataObjects\Auth\RegistrationData;
use App\Models\Contracts\Mediable;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegisterAction
{
    public function __construct(
        private UpsertAvatarAction $upsertAvatarAction,
        private SendVerificationEmailAction $sendVerificationEmailAction,
        private User $user,
    ) {
    }

    public function execute(RegistrationData $data): User
    {
        return DB::transaction(function () use ($data): User {
            /** @var User&Mediable */
            $user = $this->user->query()
                ->create($data->allForCreation());

            if ($data->hasAvatarFile()) {
                $this->upsertAvatarAction->execute(
                    file: $data->avatar,
                    model: $user,
                );
            }

            $this->sendVerificationEmailAction->execute($user->id);

            return $user->load('avatar');
        });
    }
}
