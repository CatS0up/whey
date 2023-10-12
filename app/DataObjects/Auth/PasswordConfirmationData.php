<?php

declare(strict_types=1);

namespace App\DataObjects\Auth;

use App\Http\Requests\Auth\PasswordConfirmationRequest;
use Spatie\LaravelData\Data;

final class PasswordConfirmationData extends Data
{
    public function __construct(
        public readonly int $user_id,
        public readonly string $password,
    ) {
    }

    public static function fromFormRequest(PasswordConfirmationRequest $request): self
    {
        return self::from([
            'user_id' => $request->user()->id(),
            'password' => $request->password,
        ]);
    }
}
