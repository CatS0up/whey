<?php

declare(strict_types=1);

namespace App\Http\Livewire\Concerns;

use App\ValueObjects\Livewire\SweetAlert2\Modal;

trait SweetAlert2
{
    public function openModal(Modal $modal): void
    {
        $this->dispatchBrowserEvent(
            event: 'swal:modal',
            data: $modal->toArray(),
        );
    }
}
