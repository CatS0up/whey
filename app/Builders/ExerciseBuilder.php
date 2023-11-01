<?php

declare(strict_types=1);

namespace App\Builders;

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
    public function verify(int $verifierId): void
    {
        $this->model->verifier_id = $verifierId;
        $this->model->verified_at = now();
        $this->model->save();
    }

    /** Model methods - start */
    public function unverify(): void
    {
        $this->model->verifier_id = null;
        $this->model->verified_at = null;
        $this->model->save();
    }

    public function isVerified(): bool
    {
        return isset($this->model->verified_at);
    }
    /** Model methods - end */
}
