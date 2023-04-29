<?php

declare(strict_types=1);

namespace App\DataObjects;

use App\Enums\DifficultyLevel;
use App\Enums\ExerciseType;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

final class ExerciseData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly ?string $instructions_raw,
        public readonly string $instructions_html,
        public readonly ?Carbon $verified_at,
        #[WithCast(EnumCast::class)]
        public readonly DifficultyLevel $difficulty,
        #[WithCast(EnumCast::class)]
        public readonly ExerciseType $exercise_type,
        public readonly FileData $thumbnail,
    ) {
    }
}
