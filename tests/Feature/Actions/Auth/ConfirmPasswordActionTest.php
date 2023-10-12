<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Auth;

use App\Actions\Auth\ConfirmPasswordAction;
use App\DataObjects\Auth\PasswordConfirmationData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class ConfirmPasswordActionTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    private ConfirmPasswordAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(ConfirmPasswordAction::class);
    }

    public function getUserFactoryAttributes(): array
    {
        return [
            'password' => 'dummy_password',
        ];
    }

    /** @test */
    public function it_should_return_false_when_the_given_password_is_not_equal_to_the_given_user_hashed_password(): void
    {
        $actual = $this->actionUnderTest->execute(PasswordConfirmationData::from([
            'user_id' => $this->user->id,
            'password' => 'im not a correct password',
        ]));

        $this->assertFalse($actual);
    }

    /** @test */
    public function it_should_does_not_set_session_set_session_value_with_destination_path_when_the_given_password_is_not_equal_to_the_given_user_hashed_password(): void
    {
        $this->actionUnderTest->execute(PasswordConfirmationData::from([
            'user_id' => $this->user->id,
            'password' => 'im not a correct password',
        ]));

        $this->assertFalse(session()->has('auth.password_confirmed_at'));
    }

    /** @test */
    public function it_should_return_true_when_the_given_password_is_equal_to_the_given_user_hashed_password(): void
    {
        $actual = $this->actionUnderTest->execute(PasswordConfirmationData::from([
            'user_id' => $this->user->id,
            'password' => 'dummy_password',
        ]));

        $this->assertTrue($actual);
    }

    /** @test */
    public function it_should_set_session_value_with_destination_path_when_the_given_password_is_equal_to_the_given_user_hashed_password(): void
    {
        $this->actionUnderTest->execute(PasswordConfirmationData::from([
            'user_id' => $this->user->id,
            'password' => 'dummy_password',
        ]));

        $this->assertTrue(session()->has('auth.password_confirmed_at'));
    }
}
