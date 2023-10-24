<?php

declare(strict_types=1);

namespace App\Enums;

enum Role: string
{
    case User = 'user';
    case Trainer = 'trainer';
    case Admin = 'admin';

    public function id(): int
    {
        return match($this) {
            self::User => 1,
            self::Trainer => 2,
            self::Admin => 3,
        };
    }

    public function label(): string
    {
        // TODO: Tłumaczenia
        return match ($this) {
            self::User => 'User',
            self::Trainer => 'Trainer',
            self::Admin => 'Admin',
        };
    }
}
