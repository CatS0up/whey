<?php

declare(strict_types=1);

namespace App\ViewModels\Exercise;

use App\DataObjects\ExerciseData;
use App\DataObjects\MuscleData;
use App\Models\Exercise;
use App\ViewModels\ViewModel;
use Illuminate\Support\Collection;

class ExerciseReviewViewModel extends ViewModel
{
    private readonly ExerciseData $exerciseData;

    public function __construct(Exercise $exercise)
    {
        $this->exerciseData = $exercise->load('author', 'thumbnail', 'muscles')->getData();
    }

    public function exercise_thumbnail_path(): string
    {
        if ($thumbnail = $this->exerciseData->thumbnail->resolve()) {
            return $thumbnail->full_path;
        }

        return asset('images/placeholders/big_placeholder.png');
    }

    /**
     * @return Collection<MuscleData>
     */
    public function exerciseMuscles(): Collection
    {
        return $this->exerciseData
            ->muscles
            ->resolve()
            ->toCollection();
    }

    public function exercise(): ExerciseData
    {
        return $this->exerciseData;
    }
}
