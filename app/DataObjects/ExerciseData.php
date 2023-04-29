<?php

declare(strict_types=1);

namespace App\DataObjects;

use App\Enums\DifficultyLevel;
use App\Enums\ExerciseType;
use Carbon\Carbon;
use Livewire\Wireable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Data;

final class ExerciseData extends Data implements Wireable
{
    use WireableData;

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
