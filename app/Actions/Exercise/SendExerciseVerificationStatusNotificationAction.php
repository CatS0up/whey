<?php

declare(strict_types=1);

namespace App\Actions\Exercise;

use App\Enums\ExerciseStatus;
use App\Exceptions\Exercise\ExerciseStatusNotVerifiedOrRejected;
use App\Exceptions\Exercise\ReviewExplanationHasNotBeenPassed;
use App\Models\Exercise;
use App\Notifications\Exercise\RejectedExerciseNotification;
use App\Notifications\Exercise\VerifiedExerciseNotification;

class SendExerciseVerificationStatusNotificationAction
{
    public function __construct(
        private Exercise $exercise,
    ) {
    }

    /**
     * @param string|null $explanation Required only when the exercise has a status of 'rejected'. Otherwise, it is ignored.
     */
    public function execute(int $exerciseId, ?string $explanation = null): void
    {
        /** @var Exercise $exercise */
        $exercise = $this->exercise->query()->findOrFail($exerciseId);

        $this->ensureValidStatus($exercise->status);
        $this->ensureExplanationPassed($exercise, $explanation);

        if ($exercise->status->isRejected()) {
            $exercise->author->notify(new RejectedExerciseNotification(
                exercise: $exercise,
                explanation: $explanation,
            ));

            return;
        }

        $exercise->author->notify(new VerifiedExerciseNotification($exercise));
    }

    private function ensureExplanationPassed(Exercise $exercise, ?string $explanation): void
    {
        if ($exercise->status->isRejected() && empty($explanation)) {
            throw ReviewExplanationHasNotBeenPassed::because('In the case of a rejected exercise, explanations are required');
        }
    }

    private function ensureValidStatus(ExerciseStatus $status): void
    {
        if ( ! in_array($status, [ExerciseStatus::Verified, ExerciseStatus::Rejected])) {
            throw ExerciseStatusNotVerifiedOrRejected::because('The exercise must have a status of either verified or rejected');
        }
    }
}
