<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Exercise;

class ExerciseObserver
{
    public function creating(Exercise $exercise): void
    {
        $this->prepareInstructionsRawValue($exercise);
    }

    public function updating(Exercise $exercise): void
    {
        $this->prepareInstructionsRawValue($exercise);
    }

    private function prepareInstructionsRawValue(Exercise $exercise): void
    {
        if ($exercise->isDirty('instructions_html')) {
            $exercise->instructions_raw = strip_tags($exercise->instructions_html);
        }
    }
}
