<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Exercise;
use App\Models\User;

class ExercisePolicy
{
    public function create(User $user): bool
    {
        return $user->can('create-exercises');
    }

    public function update(User $user, Exercise $exercise): bool
    {
        return $user->can('edit-exercises') || $this->isAuthor($user, $exercise);
    }

    public function delete(User $user, Exercise $exercise): bool
    {
        return $user->can('delete-exercises') || $this->isAuthor($user, $exercise);
    }

    private function isAuthor(User $user, Exercise $exercise): bool
    {
        return $exercise->author->id === $user->id;
    }
}
