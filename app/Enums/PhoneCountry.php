<?php

declare(strict_types=1);

namespace App\Enums;

enum PhoneCountry: string
{
    case Poland = 'PL';
    case GreatBritain = 'GB';
    case Usa = 'US';

    // TODO: Tłumaczenia
    public function label(): string
    {
        return "{$this->emoji()} {$this->value}";
    }

    public function emoji(): string
    {
        return match ($this) {
            self::Poland => '🇵🇱',
            self::GreatBritain => '🇬🇧',
            self::Usa => '🇺🇸',
        };
    }
}
