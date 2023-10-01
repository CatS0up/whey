<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Auth;

use App\Actions\Auth\LoginAction;
use App\DataObjects\Auth\LoginData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class LoginActionTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    private LoginAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(LoginAction::class);
    }

    /** @test */
    public function user_can_not_sign_by_incorrect_email_and_correct_password(): void
    {
        $actual = $this->actionUnderTest->execute(LoginData::from([
            'login' => 'iminvalidemail@address.com',
            'password' => 'test',
        ]));

        $this->assertFalse($actual);
    }

    /** @test */
    public function user_can_not_sign_by_incorrect_phone_and_correct_password(): void
    {
        $actual = $this->actionUnderTest->execute(LoginData::from([
            'login' => '987654321',
            'password' => 'test',
        ]));

        $this->assertFalse($actual);
    }

    /** @test */
    public function user_can_sign_in_when_email_and_password_are_correct(): void
    {
        $actual = $this->actionUnderTest->execute(LoginData::from([
            'login' => $this->user->email,
            'password' => 'password',
        ]));

        $this->assertTrue($actual);
    }

    /** @test */
    public function user_can_sign_in_when_phone_and_password_are_correct(): void
    {
        $actual = $this->actionUnderTest->execute(LoginData::from([
            'login' => (string) $this->user->phone,
            'password' => 'password',
        ]));

        $this->assertTrue($actual);
    }
}
