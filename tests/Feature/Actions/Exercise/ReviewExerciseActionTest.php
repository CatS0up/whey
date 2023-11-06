<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Exercise;

use App\Actions\Exercise\ReviewExerciseAction;
use App\DataObjects\Exercise\ExerciseReviewData;
use App\Enums\ExerciseStatus;
use App\Exceptions\Exercise\ExerciseHasNotReviewableStatus;
use App\Models\Exercise;
use Tests\Abstracts\ExerciseTestCase;

class ReviewExerciseActionTest extends ExerciseTestCase
{
    private ReviewExerciseAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(ReviewExerciseAction::class);
    }

    /**
     * @dataProvider Tests\Abstracts\ExerciseTestCase::notReviewableStatusesProvider
     * @test
     */
    public function it_should_throw_ExerciseHasNotReviewableStatus_when_given_exercise_has_not_reviewable_status(ExerciseStatus $status): void
    {
        $this->expectException(ExerciseHasNotReviewableStatus::class);
        $this->expectExceptionMessage('Only exercises with the \'for_verification\' and \'rejected\' statuses can be verified');

        $exercise = Exercise::factory()->create(['status' => $status]);

        $this->actionUnderTest->execute(
            ExerciseReviewData::from([
                'exercise_id' => $exercise->id,
                'reviewer_id' => $this->user->id,
                'status' => $status,
            ]),
        );
    }

    /**
     * @dataProvider Tests\Abstracts\ExerciseTestCase::reviewableStatusesProvider
     * @test
     */
    public function it_should_verify_the_given_exercise_when_it_has_a_reviewable_status(ExerciseStatus $status): void
    {
        $exercise = Exercise::factory()->create([
            'status' => $status,
        ]);

        $verified = $this->actionUnderTest->execute(
            ExerciseReviewData::from([
                'exercise_id' => $exercise->id,
                'reviewer_id' => $this->user->id,
                'status' => ExerciseStatus::Verified,
            ]),
        );

        $this->assertDatabaseHas('exercises', [
            'id' => $verified->id,
            'reviewer_id' => $verified->reviewer->id,
            'status' => $verified->status,
        ]);
    }

    /**
     * @dataProvider Tests\Abstracts\ExerciseTestCase::reviewableStatusesProvider
     * @test
     */
    public function it_should_reject_the_given_exercise_when_it_has_a_reviewable_status(ExerciseStatus $status): void
    {
        $exercise = Exercise::factory()->create([
            'status' => $status,
        ]);

        $verified = $this->actionUnderTest->execute(
            ExerciseReviewData::from([
                'exercise_id' => $exercise->id,
                'reviewer_id' => $this->user->id,
                'status' => ExerciseStatus::Rejected,
            ]),
        );

        $this->assertDatabaseHas('exercises', [
            'id' => $verified->id,
            'reviewer_id' => $verified->reviewer->id,
            'status' => $verified->status,
        ]);
    }
}
