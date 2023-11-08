<?php

declare(strict_types=1);

namespace Tests\Feature\Web\Exercise;

use App\Enums\ExerciseStatus;
use App\Http\Livewire\Exercise\ExerciseReviewForm;
use App\Models\Exercise;
use App\Models\Muscle;
use Tests\Abstracts\ExerciseTestCase;
use Tests\Concerns\Authorization;

class ExerciseReviewTest extends ExerciseTestCase
{
    use Authorization;

    /** @test */
    public function guest_user_should_be_redirected_to_the_login_page_when_they_try_to_display_review_page(): void
    {
        $exercise = Exercise::factory()->create();

        $this->get("/exercises/{$exercise->slug}/review")
            ->assertRedirect('/login');
    }

    /** @test */
    public function auth_user_without_permission_to_review_exercises_cannot_display_exercise_review_page(): void
    {
        $exercise = Exercise::factory()->create();

        $this->authenticated()
            ->get("/exercises/{$exercise->slug}/review")
            ->assertForbidden();
    }

    /**
     * @dataProvider \Tests\Abstracts\ExerciseTestCase::reviewableStatusesProvider
     *
     * @test
     * */
    public function auth_user_with_permission_to_review_exercises_can_display_exercise_review_page_when_given_exercise_has_reviewable_status(ExerciseStatus $status): void
    {
        $exercise = Exercise::factory()
            ->create([
                'status' => $status,
            ]);

        $this->assignPermissionToUser($this->user, 'review exercises');

        $this->authenticated()
            ->get("/exercises/{$exercise->slug}/review")
            ->assertOk();
    }

    /**
     * @dataProvider \Tests\Abstracts\ExerciseTestCase::notReviewableStatusesProvider
     *
     * @test
     * */
    public function auth_user_with_permission_to_review_exercises_should_be_redirected_to_exercises_index_page_when_they_try_to_review_exercise_with_not_reviewable_status(ExerciseStatus $status): void
    {
        $exercise = Exercise::factory()->create(['status' => $status]);

        $this->assignPermissionToUser($this->user, 'review exercises');

        $response = $this->authenticated()
            ->get("/exercises/{$exercise->slug}/review")
            ->assertRedirect('/exercises')
            ->assertSessionHas('warning', 'The given exercise has not reviewable status. It has probably already been verified by another user');

        $this->followRedirects($response)
            ->assertSee('The given exercise has not reviewable status. It has probably already been verified by another user');
    }

    /**
     * @dataProvider \Tests\Abstracts\ExerciseTestCase::reviewableStatusesProvider
     *
     * @test
     * */
    public function auth_user_with_permission_to_review_exercises_can_see_verifying_exercise_data_on_the_review_page_when_given_exercise_has_reviewable_status(ExerciseStatus $status): void
    {
        $exercise = Exercise::factory()
            ->has(Muscle::factory(10))
            ->create([
                'status' => $status,
            ]);

        $this->assignPermissionToUser($this->user, 'review exercises');

        $this->authenticated()
            ->get("/exercises/{$exercise->slug}/review")
            ->assertSeeText($exercise->name)
            ->assertSeeText($exercise->author->name)
            ->assertSeeText($exercise->type->label())
            ->assertSeeText($exercise->difficulty_level->label())
            ->assertSeeText($exercise->muscles->pluck('name')->join(', '))
            ->assertSee($exercise->instructions_html, false);
    }

    /**
     * @dataProvider \Tests\Abstracts\ExerciseTestCase::reviewableStatusesProvider
     *
     * @test
     * */
    public function auth_user_with_permission_to_review_exercises_can_see_ExerciseReviewForm_livewire_component_on_the_review_page_when_given_exercise_has_reviewable_status(ExerciseStatus $status): void
    {
        $exercise = Exercise::factory()
            ->has(Muscle::factory(10))
            ->create(['status' => $status]);

        $this->assignPermissionToUser($this->user, 'review exercises');

        $this->authenticated()
            ->get("/exercises/{$exercise->slug}/review")
            ->assertOk()
            ->assertSeeLivewire(ExerciseReviewForm::class);
    }
}
