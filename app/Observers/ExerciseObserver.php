<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\ExerciseStatus;
use App\Models\Exercise;

class ExerciseObserver
{
    public function saving(Exercise $exercise): void
    {
        $this->assignInitialStatus($exercise);
        $this->prepareInstructionsRawValue($exercise);
    }

    private function prepareInstructionsRawValue(Exercise $exercise): void
    {
        if ($exercise->isDirty('instructions_html')) {
            $exercise->instructions_raw = strip_tags($exercise->instructions_html);
        }
    }

    private function assignInitialStatus(Exercise $exercise): void
    {
        if (empty($exercise->status)) {
            if ($exercise->is_public) {
                $exercise->status = ExerciseStatus::ForVerification;
                return;
            }

            $exercise->status = ExerciseStatus::Private;
            return;
        }
    }
}
