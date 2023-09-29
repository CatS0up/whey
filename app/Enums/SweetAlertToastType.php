<?php

declare(strict_types=1);

namespace App\Enums;

enum SweetAlertToastType
{
    case Success;
    case Info;
    case Warning;
    case Error;

    public function type(): string
    {
        return match($this) {
            self::Success => 'success',
            self::Info => 'info',
            self::Warning => 'warning',
            self::Error => 'error',
        };
    }
}
