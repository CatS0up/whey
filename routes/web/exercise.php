<?php

declare(strict_types=1);

use App\Http\Controllers\Web\Exercise\ExerciseReviewController;
use Illuminate\Support\Facades\Route;

/** EXERCISES VERIFICATION - START */
Route::middleware('can:review-exercises')
    ->prefix('{exercise:slug}/review')
    ->as('verification.')
    ->controller(ExerciseReviewController::class)
    ->group(function (): void {
        Route::get('/', 'show')
            ->name('show');
    });
/** EXERCISES VERIFICATION - END */
