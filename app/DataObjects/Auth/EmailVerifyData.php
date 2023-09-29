<?php

declare(strict_types=1);

namespace App\DataObjects\Auth;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

final class EmailVerifyData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly int $user_id,
        public readonly string $token,
        public readonly Carbon $expire_at,
        public readonly Carbon $created_at,
    ) {
    }
}
