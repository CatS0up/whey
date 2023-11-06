<?php

declare(strict_types=1);

namespace App\Validators\Exercise;

use App\Enums\ExerciseStatus;
use App\Http\Livewire\Exercise\ExerciseReviewForm;
use Illuminate\Validation\Rule;

class ExerciseReviewValidator
{
    public function rulesForExerciseReviewForm(ExerciseReviewForm $context): array
    {
        return [
            'status' => [
                'required',
                Rule::in(array_column(ExerciseStatus::cases(), 'value')),
            ],
            'explanation' => [
                Rule::requiredIf(ExerciseStatus::Rejected->value === $context->status),
                'nullable',
                'string',
                'max:65535',
            ],
        ];
    }
}
