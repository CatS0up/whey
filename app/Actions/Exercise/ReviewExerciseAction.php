<?php

declare(strict_types=1);

namespace App\Actions\Exercise;

use App\DataObjects\Exercise\ExerciseReviewData;
use App\Exceptions\Exercise\ExerciseHasNotReviewableStatus;
use App\Models\Exercise;

class ReviewExerciseAction
{
    public function __construct(private Exercise $exercise)
    {
    }

    public function execute(ExerciseReviewData $data): Exercise
    {
        /** @var Exercise $exercise */
        $exercise = $this->exercise->query()
            ->findOrFail($data->exercise_id);

        $this->ensureExerciseIsReviewable($exercise->isNotReviewable());

        if ($data->status->isVerified()) {
            $this->markExerciseAsVerified($exercise, $data->reviewer_id);
            return $exercise;
        }

        $this->markExerciseAsRejected($exercise, $data->reviewer_id);
        return $exercise;
    }

    private function markExerciseAsVerified(Exercise $exercise, int $reviewerId): Exercise
    {
        return tap($exercise, fn () => $exercise->verify($reviewerId));
    }

    private function markExerciseAsRejected(Exercise $exercise, int $reviewerId): Exercise
    {
        return tap($exercise, fn () => $exercise->verify($reviewerId));
    }

    private function ensureExerciseIsReviewable(bool $isNotReviewable): void
    {
        if ($isNotReviewable) {
            throw ExerciseHasNotReviewableStatus::because('Only exercises with the \'for_verification\' and \'rejected\' statuses can be verified');
        }
    }
}
