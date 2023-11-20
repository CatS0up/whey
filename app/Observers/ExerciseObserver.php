<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Exercise;
use App\Support\HtmlPurifier;

class ExerciseObserver
{
    public function saving(Exercise $exercise): void
    {
        $this->prepareInstructionsFields($exercise);
    }

    private function prepareInstructionsFields(Exercise $exercise): void
    {
        if ($exercise->isDirty('instructions_html')) {
            $exercise->instructions_html = HtmlPurifier::purify($exercise->instructions_html);
            $exercise->instructions_raw = HtmlPurifier::convertToRawText($exercise->instructions_html);
        }
    }
}
