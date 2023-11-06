<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Exercise;

use App\Actions\Exercise\SendExerciseVerificationStatusNotificationAction;
use App\Enums\ExerciseStatus;
use App\Exceptions\Exercise\ExerciseStatusNotVerifiedOrRejected;
use App\Exceptions\Exercise\ReviewExplanationHasNotBeenPassed;
use App\Models\Exercise;
use App\Notifications\Exercise\RejectedExerciseNotification;
use App\Notifications\Exercise\VerifiedExerciseNotification;
use Illuminate\Support\Facades\Notification;
use Tests\Abstracts\ExerciseTestCase;

class SendExerciseVerificationStatusNotificationActionTest extends ExerciseTestCase
{
    private SendExerciseVerificationStatusNotificationAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(SendExerciseVerificationStatusNotificationAction::class);
    }

    /** @test */
    public function it_should_throw_ReviewExplanationHasNotBeenPassed_exception_when_the_given_status_is_rejected_and_explanation_has_not_been_passed(): void
    {
        $this->expectException(ReviewExplanationHasNotBeenPassed::class);
        $this->expectExceptionMessage('In the case of a rejected exercise, explanations are required');

        $exercise = Exercise::factory()->create(['status' => ExerciseStatus::Rejected]);

        $this->actionUnderTest->execute(
            exerciseId: $exercise->id,
        );
    }

    /**
     * @dataProvider invalidStatuses
     *
     * @test
     * */
    public function it_should_throw_ExerciseStatusNotVerifiedOrRejected_exception_when_the_given_status_is_not_equal_to_verified_or_rejected(ExerciseStatus $status): void
    {
        $this->expectException(ExerciseStatusNotVerifiedOrRejected::class);
        $this->expectExceptionMessage('The exercise must have a status of either verified or rejected');

        $exercise = Exercise::factory()->create(['status' => $status]);

        $this->actionUnderTest->execute(
            exerciseId: $exercise->id,
        );
    }

    /** @test */
    public function it_shoud_send_a_RejectedExerciseNotification_when_the_given_notification_has_been_rejected(): void
    {
        Notification::fake();

        $exercise = Exercise::factory()->create(['status' => ExerciseStatus::Rejected]);

        $this->actionUnderTest->execute(
            exerciseId: $exercise->id,
            explanation: 'Some explanation',
        );

        Notification::assertSentTo(
            notifiable: $exercise->author,
            notification: RejectedExerciseNotification::class,
            callback: fn (mixed $notification, array $channels): bool => in_array('mail', $channels),
        );
    }

    /** @test */
    public function it_shoud_send_a_VerifiedExerciseNotification_when_the_given_notification_has_been_verified(): void
    {
        Notification::fake();

        $exercise = Exercise::factory()->create(['status' => ExerciseStatus::Verified]);

        $this->actionUnderTest->execute(
            exerciseId: $exercise->id,
        );

        Notification::assertSentTo(
            notifiable: $exercise->author,
            notification: VerifiedExerciseNotification::class,
            callback: fn (mixed $notification, array $channels): bool => in_array('mail', $channels),
        );
    }

    public static function invalidStatuses(): array
    {
        return [
            'for_verfication status' => [
                ExerciseStatus::ForVerification,
            ],
            'private status' => [
                ExerciseStatus::Private,
            ],
        ];
    }
}
