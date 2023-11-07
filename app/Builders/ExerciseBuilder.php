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

    public function makePrivate(): void
    {
        $this->model->reviewer_id = null;
        $this->model->reviewed_at = null;
        $this->model->status = ExerciseStatus::Private;
        $this->model->save();
    }

    public function makeForVerification(): void
    {
        $this->model->reviewer_id = null;
        $this->model->reviewed_at = null;
        $this->model->status = ExerciseStatus::ForVerification;
        $this->model->save();
    }
    /** Model methods - end */
}
