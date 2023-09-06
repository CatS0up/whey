<?php

declare(strict_types=1);

namespace App\DataObjects;

use App\Enums\MuscleGroup;
use App\Models\Muscle;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

final class MuscleData extends Data
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly string $name,
        #[WithCast(EnumCast::class)]
        public readonly MuscleGroup $muscle_group,
        public readonly Lazy|FileData $thumbnail,
        public readonly Lazy|FileData $small_thumbnail,
    ) {
    }

    public static function fromModel(Muscle $muscle): self
    {
        return self::from([
            ...$muscle->toArray(),
            'thumbnail' => Lazy::whenLoaded(
                relation: 'thumbnail',
                model: $muscle,
                value: fn (): FileData => $muscle->thumbnail->getData(),
            ),
            'small_thumbnail' => Lazy::whenLoaded(
                relation: 'small_thumbnail',
                model: $muscle,
                value: fn (): FileData => $muscle->smallThumbnail->getData(),
            ),
        ]);
    }
}
