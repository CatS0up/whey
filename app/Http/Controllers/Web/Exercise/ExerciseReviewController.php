<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Exercise;

use App\Exceptions\Exercise\ExerciseHasNotReviewableStatus;
use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\ViewModels\Exercise\ExerciseReviewViewModel;
use Illuminate\View\View;

class ExerciseReviewController extends Controller
{
    public function show(Exercise $exercise): View
    {
        if ($exercise->isNotReviewable()) {
            throw ExerciseHasNotReviewableStatus::because('Only exercises with the \'for_verification\' and \'rejected\' statuses can be verified');
        }

        return view(
            view: 'web.sections.exercise.subviews.review',
            data: [
                'model' => (new ExerciseReviewViewModel($exercise))->toArray(),
            ],
        );
    }
}
