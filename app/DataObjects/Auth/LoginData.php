<?php

declare(strict_types=1);

namespace App\DataObjects\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Spatie\LaravelData\Data;

final class LoginData extends Data
{
    public function __construct(
        public readonly string $login,
        public readonly string $password,
        public readonly bool $remember_me = false,
    ) {
    }

    public static function fromFormRequest(LoginRequest $request): self
    {
        return self::from([
            'login' => $request->login,
            'password' => $request->password,
            'remember_me' => $request->remember_me,
        ]);
    }

    public function loginIsEmail(): bool
    {
        return (bool) filter_var(
            value: $this->login,
            filter: FILTER_VALIDATE_EMAIL,
        );
    }

    public function toCredentials(): array
    {
        if ($this->loginIsEmail()) {
            return [
                'email' => $this->login,
                'password' => $this->password,
            ];
        }

        return [
            'phone' => $this->login,
            'password' => $this->password,
        ];
    }
}
