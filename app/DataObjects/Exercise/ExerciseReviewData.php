<?php

declare(strict_types=1);

namespace App\DataObjects\Exercise;

use App\Enums\ExerciseStatus;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

final class ExerciseReviewData extends Data
{
    public function __construct(
        public readonly int $exercise_id,
        public readonly int $reviewer_id,
        #[WithCast(EnumCast::class)]
        public readonly ExerciseStatus $status,
    ) {
    }
}
