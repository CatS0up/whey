<?php

declare(strict_types=1);

namespace App\DataObjects\User;

use App\Http\Requests\User\UpdatePasswordRequest;
use Spatie\LaravelData\Data;

final class UpdatePasswordData extends Data
{
    public function __construct(
        public readonly int $user_id,
        public readonly string $password,
    ) {
    }

    public static function fromFormRequest(UpdatePasswordRequest $request): self
    {
        return self::from([
            'user_id' => $request->user()->id(),
            'password' => $request->password,
        ]);
    }
}
