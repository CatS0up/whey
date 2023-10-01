<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Auth;

use App\Actions\Auth\LogoutAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class LogoutActionTest extends TestCase
{
    use RefreshDatabase;
    use Authentication;

    private LogoutAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(LogoutAction::class);
    }

    /** @test */
    public function it_should_return_false_when_try_call_action_when_user_is_logged_out(): void
    {
        $this->assertFalse(auth()->check());

        $actual = $this->actionUnderTest->execute();

        $this->assertFalse($actual);
    }

    /** @test */
    public function it_should_log_out_auth_user_and_return_true_result(): void
    {
        $this->authenticated();

        $actual = $this->actionUnderTest->execute();

        $this->assertTrue($actual);
    }
}
