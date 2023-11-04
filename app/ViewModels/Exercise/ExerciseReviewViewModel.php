<?php

declare(strict_types=1);

namespace App\ViewModels\Exercise;

use App\DataObjects\ExerciseData;
use App\Models\Exercise;
use App\ViewModels\ViewModel;
use Spatie\LaravelData\Contracts\DataObject;

class ExerciseReviewViewModel extends ViewModel
{
    public function __construct(private readonly Exercise $exercise)
    {
    }

    public function exercise(): ExerciseData|DataObject|null
    {
        return $this->exercise
            ->load('thumbnail', 'muscles')
            ->getData();
    }
}
