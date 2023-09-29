<?php

declare(strict_types=1);

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

class EmailVerifyBuilder extends Builder
{
    public function whereActive(): self
    {
        return $this->where('expire_at', '>', now());
    }

    public function whereIsActiveForUser(int $userId): self
    {
        return $this->whereRelation('user', 'id', $userId)
            ->whereActive();
    }
}
