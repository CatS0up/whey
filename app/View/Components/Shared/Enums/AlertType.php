<?php

declare(strict_types=1);

namespace App\View\Components\Shared\Enums;

enum AlertType
{
    case SUCCESS;
    case ERROR;
    case INFO;
    case WARNING;

    public function color(): string
    {
        return match($this) {
            self::SUCCESS => 'green',
            self::ERROR => 'red',
            self::INFO => 'blue',
            self::WARNING => 'orange',
        };
    }
}
