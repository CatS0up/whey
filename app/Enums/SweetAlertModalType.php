<?php

declare(strict_types=1);

namespace App\Enums;

enum SweetAlertModalType
{
    case Success;
    case Error;
    case Warning;
    case Question;

    public function icon(): string
    {
        return match ($this) {
            self::Success => 'success',
            self::Error => 'error',
            self::Warning => 'warning',
            self::Question => 'question',
        };
    }
}
