<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enums\ExerciseStatus;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Exercise;

/**
 * @template TModelClass of \App\Models\Exercise
 * @extends Builder<TModelClass>
 *
 * @property Exercise $model
 */
class ExerciseBuilder extends Builder
{
    /** Model methods - start */
    public function verify(int $reviewerId): void
    {
        $this->model->reviewer_id = $reviewerId;
        $this->model->reviewed_at = now();
        $this->model->status = ExerciseStatus::Verified;
        $this->model->save();
    }

    public function reject(int $reviewerId): void
    {
        $this->model->reviewer_id = $reviewerId;
        $this->model->reviewed_at = now();
        $this->model->status = ExerciseStatus::Rejected;
        $this->model->save();
    }

    public function isVerified(): bool
    {
        return $this->model->status->isVerified();
    }

    public function isForVerification(): bool
    {
        return $this->model->status->isForVerification();
    }

    public function isRejected(): bool
    {
        return $this->model->status->isRejected();
    }

    public function isPrivate(): bool
    {
        return $this->model->status->isPrivate();
    }

    public function isReviewable(): bool
    {
        return $this->model->status->isReviewable();
    }

    public function isNotReviewable(): bool
    {
        return $this->model->status->isNotReviewable();
    }
    /** Model methods - end */
}
