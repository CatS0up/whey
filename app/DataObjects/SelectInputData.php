<?php

declare(strict_types=1);

namespace App\DataObjects;

use Spatie\LaravelData\Data;

final class SelectInputData extends Data
{
    public function __construct(
        public readonly mixed $value,
        public readonly string $label,
    ) {
    }
}
