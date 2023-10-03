<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Auth;

use App\Actions\Auth\GenerateTemporaryPasswordAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class GenerateTemporaryPasswordActionTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    private GenerateTemporaryPasswordAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(GenerateTemporaryPasswordAction::class);
    }

    /** @test */
    public function it_should_generate_temporary_password_for_given_user(): void
    {
        $this->assertTrue(Hash::check('password', $this->user->password));

        $tempPassword = $this->actionUnderTest->execute($this->user->id);

        $this->refreshUser();
        $this->assertTrue(Hash::check($tempPassword, $this->user->password));
    }

    /** @test */
    public function it_should_mark_user_as_temporary_password_owner_when_temporary_password_has_been_generated(): void
    {
        $this->assertFalse($this->user->hasTemporaryPassword());

        $this->actionUnderTest->execute($this->user->id);

        $this->refreshUser();
        $this->assertTrue($this->user->hasTemporaryPassword());
    }
}
