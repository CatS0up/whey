<?php

declare(strict_types=1);

namespace App\DataObjects;

use App\Models\Media;
use Spatie\LaravelData\Data;

final class FileData extends Data
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly string $file_name,
        public readonly string $mime_type,
        public readonly ?string $path = null,
        public readonly string $disk,
        public readonly string $file_hash,
        public readonly ?string $collection = null,
        public readonly int $size,
    ) {}
}
