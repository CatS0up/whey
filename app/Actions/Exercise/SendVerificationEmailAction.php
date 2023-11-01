<?php

declare(strict_types=1);

namespace App\Actions\Exercise;

use App\Exceptions\Authorization\AuthorizedUsersNotFound;
use App\Models\Exercise;
use App\Models\User;
use App\Notifications\Exercise\ExerciseVerificationNotification;
use Illuminate\Support\Facades\Notification;

class SendVerificationEmailAction
{
    public function __construct(
        private User $user,
        private Exercise $exercise,
    ) {
    }

    public function execute(int $id): void
    {
        $authorizedUsers = $this->user->query()
            ->wherePermissionTo('verify-exercises')
            ->get();

        if ($authorizedUsers->isEmpty()) {
            throw AuthorizedUsersNotFound::because('In your system, there are no users who have the \'verify-exercises\' permission');
        }

        /** @var Exercise $exercise */
        $exercise = $this->exercise->query()->findOrFail($id);
        Notification::send(
            notifiables: $authorizedUsers,
            notification: new ExerciseVerificationNotification($exercise),
        );
    }
}
