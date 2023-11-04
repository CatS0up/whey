<?php

declare(strict_types=1);

namespace App\Enums;

enum ExerciseStatus: string
{
    case Verified = 'verified';
    case Rejected = 'rejected';

    public function isVerified(): bool
    {
        return self::Verified === $this;
    }

    public function isRejected(): bool
    {
        return self::Rejected === $this;
    }
}
