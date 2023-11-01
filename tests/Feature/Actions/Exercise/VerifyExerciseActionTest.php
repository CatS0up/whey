<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Exercise;

use App\Actions\Exercise\VerifyExerciseAction;
use App\Exceptions\Exercise\ExerciseHasBeenVerified;
use App\Models\Exercise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class VerifyExerciseActionTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    private VerifyExerciseAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(VerifyExerciseAction::class);
    }

    /** @test */
    public function it_should_throw_ExerciseHasBeenVerified_exception_when_given_exception_wash_verified(): void
    {
        $this->expectException(ExerciseHasBeenVerified::class);
        $this->expectExceptionMessage('Given exercise has been verified');

        $exercise = Exercise::factory()->for($this->user, 'author')->create();

        $this->actionUnderTest->execute($exercise->id, $this->user->id);
    }

    /** @test */
    public function it_should_verify_given_exercise_when_exercise_is_not_verified(): void
    {
        $exercise = Exercise::factory()
            ->unverified()
            ->for($this->user, 'author')
            ->create();

        /** @var Exercise $exercise */
        $exercise = $this->actionUnderTest->execute($exercise->id, $this->user->id);

        $this->assertInstanceOf(Exercise::class, $exercise);
        $this->assertTrue($exercise->isVerified());
        $this->assertTrue($exercise->verifier()->exists());
        $this->assertEquals($exercise->verifier->id, $this->user->id);
    }
}
