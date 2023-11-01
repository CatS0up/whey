<?php

declare(strict_types=1);

namespace App\Actions\Exercise;

use App\Exceptions\Exercise\ExerciseHasBeenVerified;
use App\Models\Exercise;

class VerifyExerciseAction
{
    public function __construct(private Exercise $exercise)
    {
    }

    public function execute(int $id, int $verifierId): Exercise
    {
        /** @var Exercise $exercise */
        $exercise = $this->exercise->query()
            ->findOrFail($id);

        if ($exercise->isVerified()) {
            throw ExerciseHasBeenVerified::because('Given exercise has been verified');
        }

        return tap(
            value: $exercise,
            callback: fn (Exercise $exercise) => $exercise->verify($verifierId),
        );
    }
}
