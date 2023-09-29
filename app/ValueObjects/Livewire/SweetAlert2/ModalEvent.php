<?php

declare(strict_types=1);

namespace App\ValueObjects\Livewire\SweetAlert2;

final class ModalEvent
{
    public function __construct(
        public readonly string $method,
        public readonly array $params,
    ) {
    }
}
