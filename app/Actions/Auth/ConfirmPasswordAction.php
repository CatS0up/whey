<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\DataObjects\Auth\PasswordConfirmationData;
use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Session\SessionManager;

class ConfirmPasswordAction
{
    public function __construct(
        private SessionManager $session,
        private Hasher $hasher,
        private User $user,
    ) {
    }

    public function execute(PasswordConfirmationData $data): bool
    {
        $user = $this->user->query()->findOrFail($data->user_id);
        $passwordsAreEqual = $this->checkPassword(raw: $data->password, hashed: $user->password);

        if ($passwordsAreEqual) {
            $this->session->passwordConfirmed();
        }

        return $passwordsAreEqual;
    }

    private function checkPassword(string $raw, string $hashed): bool
    {
        return $this->hasher->check($raw, $hashed);
    }
}
