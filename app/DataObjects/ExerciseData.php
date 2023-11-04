<?php

declare(strict_types=1);

namespace App\DataObjects;

use App\DataObjects\User\UserData;
use App\Enums\DifficultyLevel;
use App\Enums\ExerciseStatus;
use App\Enums\ExerciseType;
use App\Models\Exercise;
use App\Models\Media;
use App\Models\Muscle;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Contracts\DataObject;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;

final class ExerciseData extends Data
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly string $name,
        #[WithCast(EnumCast::class)]
        public readonly DifficultyLevel $difficulty_level,
        #[WithCast(EnumCast::class)]
        public readonly ExerciseType $type,
        #[WithCast(EnumCast::class)]
        public readonly ExerciseStatus $status,
        public readonly string $instructions_html,
        public readonly Lazy|FileData $thumbnail,
        public readonly Lazy|FileData $small_thumbnail,
        #[DataCollectionOf(MuscleData::class)]
        public readonly Lazy|DataCollection $muscles,
        public readonly Lazy|UserData $verifier,
        public readonly bool $is_public = true,
        public readonly ?string $instructions_raw = null,
        public readonly ?Carbon $review_at = null,
    ) {
    }

    public static function createFromArray(array $data): self
    {
        $thumbnailId = data_get($data, 'thumbnail_id');
        $smallThumbnailId = data_get($data, 'small_thumbnail_id');
        $muscleIds =  data_get($data, 'muscle_ids');
        $verifierId = data_get($data, 'verifier_id');

        return self::from(
            [
                'id' => data_get($data, 'id'),
                'name' => data_get($data, 'name'),
                'difficulty_level' => data_get($data, 'difficulty_level'),
                'type' => data_get($data, 'type'),
                'instructions_html' => data_get($data, 'instructions_html'),
                'is_public' => data_get($data, 'is_public', Exercise::IS_PUBLIC),
                'thumbnail' => Lazy::when(fn () => $thumbnailId, fn () => Media::findOrFail($thumbnailId)->getData()),
                'small_thumbnail' => Lazy::when(fn () => $smallThumbnailId, fn () => Media::findOrFail($smallThumbnailId)->getData()),
                'muscles' => MuscleData::collection(
                    Muscle::query()->whereIn('id', $muscleIds)->get(),
                ),
                'verifier' => Lazy::when(fn () => $verifierId, fn () => User::findOrFail($verifierId)->getData()),
            ]
        );
    }

    public static function fromModel(Exercise $exercise): self
    {
        return self::from([
            ...$exercise->toArray(),
            'thumbnail' => Lazy::whenLoaded(
                relation: 'thumbnail',
                model: $exercise,
                value: fn (): DataObject|FileData => $exercise->thumbnail->getData(),
            ),
            'small_thumbnail' => Lazy::whenLoaded(
                relation: 'small_thumbnail',
                model: $exercise,
                value: fn (): DataObject|FileData => $exercise->smallThumbnail->getData(),
            ),
            'muscles' => Lazy::whenLoaded(
                relation: 'muscles',
                model: $exercise,
                value: fn () => MuscleData::collection($exercise->muscles),
            ),
            'verifier' => Lazy::whenLoaded(
                relation: 'verifier',
                model: $exercise,
                value: fn (): DataObject|FileData => $exercise->verifier->getData(),
            ),
        ]);
    }

    public function hasId(): bool
    {
        return null !== $this->id;
    }

    public function isCreatable(): bool
    {
        return ! $this->hasId();
    }

    public function allForUpsert(): array
    {
        return Arr::except($this->all(), ['id', 'muscles', 'instructions_raw', 'verified_at']);
    }
}
