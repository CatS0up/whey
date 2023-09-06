<?php

declare(strict_types=1);

namespace App\ValueObjects\Form;

final class SelectOption
{
    public function __construct(
        public readonly string $value,
        public readonly string $label,
    ) {
    }
}
