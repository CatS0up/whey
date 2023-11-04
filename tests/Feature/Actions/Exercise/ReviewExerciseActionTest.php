<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Exercise;

use App\Actions\Exercise\ReviewExerciseAction;
use App\DataObjects\Exercise\ExerciseReviewData;
use App\Enums\ExerciseStatus;
use App\Exceptions\Exercise\ExerciseHasNotReviewableStatus;
use App\Models\Exercise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class ReviewExerciseActionTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    private ReviewExerciseAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(ReviewExerciseAction::class);
    }

    /**
     * @dataProvider notReviewableStatusesProvider
     * @test
     */
    public function it_should_throw_ExerciseHasNotReviewableStatus_when_given_exercise_has_not_reviewable_status(ExerciseStatus $status): void
    {
        $this->expectException(ExerciseHasNotReviewableStatus::class);
        $this->expectExceptionMessage('Only exercises with the \'for_verification\' and \'rejected\' statuses can be verified');

        $exercise = Exercise::factory()->for($this->user, 'author')->create(['status' => $status]);

        $this->actionUnderTest->execute(
            ExerciseReviewData::from([
                'exercise_id' => $exercise->id,
                'reviewer_id' => $this->user->id,
                'status' => $status,
            ]),
        );
    }

    /** @test */
    public function it_should_verify_given_exercise_when_it_is_ready_for_verification(): void
    {
        $exercise = Exercise::factory()->for($this->user, 'author')->create([
            'status' => ExerciseStatus::ForVerification,
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

    /** @test */
    public function it_should_reject_given_exercise_when_it_is_ready_for_verification(): void
    {
        $exercise = Exercise::factory()->for($this->user, 'author')->create([
            'status' => ExerciseStatus::ForVerification,
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

    public static function notReviewableStatusesProvider(): array
    {
        return [
            'verified status' => [
                ExerciseStatus::Verified,
            ],
            'private status' => [
                ExerciseStatus::Private,
            ],
        ];
    }
}
