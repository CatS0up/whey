<?php

declare(strict_types=1);

namespace App\DataObjects;

use Spatie\LaravelData\Data;

final class MuscleData extends Data
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly string $name,
        public readonly string $description,
        public readonly FileData $thumbnail,
    ) {
    }
}
