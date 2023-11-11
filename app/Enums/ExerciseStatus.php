<?php

declare(strict_types=1);

namespace App\Enums;

enum ExerciseStatus: string
{
    case Verified = 'verified';
    case ForVerification = 'for_verification';
    case Rejected = 'rejected';
    case Private = 'private';

    public function isVerified(): bool
    {
        return self::Verified === $this;
    }

    public function isForVerification(): bool
    {
        return self::ForVerification === $this;
    }

    public function isRejected(): bool
    {
        return self::Rejected === $this;
    }

    public function isPrivate(): bool
    {
        return self::Rejected === $this;
    }

    public function isReviewable(): bool
    {
        return in_array($this, [self::ForVerification, self::Rejected]);
    }

    public function isNotReviewable(): bool
    {
        return ! $this->isReviewable();
    }
}
