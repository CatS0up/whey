<?php

declare(strict_types=1);

namespace App\View\Components\Shared;

use App\View\Components\Shared\Enums\AlertType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    public function __construct(
        public AlertType $type,
        public string $message,
    )
    {}

    public function render(): View|Closure|string
    {
        return view('components.shared.alert');
    }
}
