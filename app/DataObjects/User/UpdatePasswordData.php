<?php

declare(strict_types=1);

namespace App\DataObjects\User;

use App\Http\Requests\User\UpdatePasswordRequest;
use Spatie\LaravelData\Data;

final class UpdatePasswordData extends Data
{
    public function __construct(
        public readonly int $userId,
        public readonly string $password,
    ) {
    }

    public static function fromFormRequest(UpdatePasswordRequest $request): self
    {
        return self::from([
            'userId' => $request->user()->id(),
            'password' => $request->password,
        ]);
    }
}
