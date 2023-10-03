<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of \App\Models\User
 * @extends Builder<TModelClass>
 */
class UserBuilder extends Builder
{
    public function firstOrFailByEmail(string $email): User
    {
        return $this->whereEmail($email)->firstOrFail();
    }
}
