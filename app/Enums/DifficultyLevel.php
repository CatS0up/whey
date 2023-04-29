<?php

declare(strict_types=1);

namespace App\Enums;

enum DifficultyLevel: string
{
    case Beginner = 'beginner';
    case Intermediate = 'intermediate';
    case Advanced = 'advanced';

    // TODO: Tłumaczenia
    public function label(): string
    {
        return match ($this) {
            self::Beginner => 'Początkujący',
            self::Intermediate => 'Średniozaawansowany',
            self::Advanced => 'Zaawansowany',
        };
    }
}
