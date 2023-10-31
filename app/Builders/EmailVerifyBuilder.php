<?php

declare(strict_types=1);

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;
use App\Models\EmailVerify;

/**
 * @template TModelClass of \App\Models\EmailVerify
 * @extends Builder<TModelClass>
 *
 * @property EmailVerify $model
 */
class EmailVerifyBuilder extends Builder
{
    /** Model methods - start */
    public function isActive(): bool
    {
        return now()->lessThan($this->model->expire_at);
    }

    public function isExpired(): bool
    {
        return now()->greaterThanOrEqualTo($this->model->expire_at);
    }
    /** Model methods - end */

    /** Builder methods - start */
    public function whereActive(): self
    {
        return $this->where('expire_at', '>', now());
    }

    public function whereIsActiveForUser(int $userId): self
    {
        return $this->whereRelation('user', 'id', $userId)
            ->whereActive();
    }
    /** Builder methods - end */
}
