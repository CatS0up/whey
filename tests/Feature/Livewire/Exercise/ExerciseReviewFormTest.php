<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Exercise;

use App\Enums\ExerciseStatus;
use App\Exceptions\Exercise\ExerciseHasNotReviewableStatus;
use App\Http\Livewire\Exercise\ExerciseReviewForm;
use App\Models\Exercise;
use App\Notifications\Exercise\RejectedExerciseNotification;
use App\Notifications\Exercise\VerifiedExerciseNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\Abstracts\ExerciseTestCase;
use Tests\Concerns\Authorization;

class ExerciseReviewFormTest extends ExerciseTestCase
{
    use Authorization;

    /** @test */
    public function auth_user_without_permission_to_review_exercises_cannot_verify_exercise(): void
    {
        $exercise = Exercise::factory()->create();
        $this->authenticated();

        Livewire::test(
            name: ExerciseReviewForm::class,
            params: [
                'exerciseId' => $exercise->id,
            ],
        )
            ->set('status', 'verified')
            ->call('submit')
            ->assertForbidden();
    }

    /**
     * @dataProvider \Tests\Abstracts\ExerciseTestCase::notReviewableStatusesProvider
     *
     * @test
     * */
    public function it_should_throw_ExerciseHasNotReviewableStatus_exception_when_the_form_tries_to_handle_an_exercise_with_a_non_reviewable_status(ExerciseStatus $status): void
    {
        $this->expectException(ExerciseHasNotReviewableStatus::class);
        $this->expectExceptionMessage('Only exercises with the \'for_verification\' and \'rejected\' statuses can be verified');

        $exercise = Exercise::factory()->create(['status' => $status]);
        $this->assignPermissionToUser($this->user, 'review-exercises');
        $this->authenticated();

        Livewire::test(
            name: ExerciseReviewForm::class,
            params: [
                'exerciseId' => $exercise->id,
            ],
        )
            ->set('status', 'verified')
            ->call('submit');
    }

    /**
     * @dataProvider \Tests\Abstracts\ExerciseTestCase::reviewableStatusesProvider
     *
     * @test
     * */
    public function auth_user_with_permission_to_review_exercises_can_verfy_the_given_exercise_when_it_has_a_reviewable_status_and_after_everything_he_should_be_redirected_to_the_exercise_list(ExerciseStatus $status): void
    {
        $exercise = Exercise::factory()->create(['status' => $status]);

        $this->assignPermissionToUser($this->user, 'review-exercises');
        $this->authenticated();

        Livewire::test(
            name: ExerciseReviewForm::class,
            params: [
                'exerciseId' => $exercise->id,
            ],
        )
            ->set('status', 'verified')
            ->call('submit')
            ->assertRedirect('/exercises')
            ->assertSessionHas('success', "The exercise named '{$exercise->name}' has been verified");
    }

    /**
     * @dataProvider \Tests\Abstracts\ExerciseTestCase::reviewableStatusesProvider
     *
     * @test
     * */
    public function auth_user_with_permission_to_review_exercise_should_see_validation_errors_when_they_reject_the_given_exercise_and_has_not_passed_a_explanation(ExerciseStatus $status): void
    {
        $exercise = Exercise::factory()->create(['status' => $status]);

        $this->assignPermissionToUser($this->user, 'review-exercises');
        $this->authenticated();

        Livewire::test(
            name: ExerciseReviewForm::class,
            params: [
                'exerciseId' => $exercise->id,
            ],
        )
            ->set('status', 'rejected')
            ->call('submit')
            ->assertHasErrors('explanation');
    }

    /**
     * @dataProvider \Tests\Abstracts\ExerciseTestCase::reviewableStatusesProvider
     *
     * @test
     * */
    public function auth_user_with_permission_to_review_exercises_can_reject_the_given_exercise_when_it_has_a_reviewable_status_and_after_everything_he_should_be_redirected_to_the_exercise_list(ExerciseStatus $status): void
    {
        $exercise = Exercise::factory()->create(['status' => $status]);

        $this->assignPermissionToUser($this->user, 'review-exercises');
        $this->authenticated();

        Livewire::test(
            name: ExerciseReviewForm::class,
            params: [
                'exerciseId' => $exercise->id,
            ],
        )
            ->set('status', 'rejected')
            ->set('explanation', 'Because yes 🙂')
            ->call('submit')
            ->assertRedirect('/exercises')
            ->assertSessionHas('success', "The exercise named '{$exercise->name}' has been rejected");
    }

    /**
     * @dataProvider \Tests\Abstracts\ExerciseTestCase::reviewableStatusesProvider
     *
     * @test
     * */
    public function it_should_send_VerifiedExerciseNotification_to_exercise_author_when_exercise_has_been_verified_and_has_a_reviewable_status(ExerciseStatus $status): void
    {
        Notification::fake();

        $exercise = Exercise::factory()->create(['status' => $status]);

        $this->assignPermissionToUser($this->user, 'review-exercises');
        $this->authenticated();

        Livewire::test(
            name: ExerciseReviewForm::class,
            params: [
                'exerciseId' => $exercise->id,
            ],
        )
            ->set('status', 'verified')
            ->call('submit');

        Notification::assertSentTo(
            notifiable: $exercise->author,
            notification: VerifiedExerciseNotification::class,
            callback: fn (mixed $notification, array $channels): bool => in_array('mail', $channels),
        );
    }

    /**
     * @dataProvider \Tests\Abstracts\ExerciseTestCase::reviewableStatusesProvider
     *
     * @test
     * */
    public function it_should_send_RejectedExerciseNotification_to_exercise_author_when_exercise_has_been_rejected_and_has_a_reviewable_status(ExerciseStatus $status): void
    {
        Notification::fake();

        $exercise = Exercise::factory()->create(['status' => $status]);

        $this->assignPermissionToUser($this->user, 'review-exercises');
        $this->authenticated();

        Livewire::test(
            name: ExerciseReviewForm::class,
            params: [
                'exerciseId' => $exercise->id,
            ],
        )
            ->set('status', 'rejected')
            ->set('explanation', 'Some explanation')
            ->call('submit');

        Notification::assertSentTo(
            notifiable: $exercise->author,
            notification: RejectedExerciseNotification::class,
            callback: fn (mixed $notification, array $channels): bool => in_array('mail', $channels),
        );
    }
}
