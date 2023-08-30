<?php

declare(strict_types=1);

namespace App\DataObjects;

use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;

final class FileData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly string $file_name,
        public readonly string $mime_type,
        public readonly string $path,
        public readonly string $disk,
        public readonly string $file_hash,
        public readonly string $collection,
        public readonly int $size,
    ) {
    }

    public function allForUpsert(): array
    {
        return Arr::except($this->all(), ['id']);
    }
}
