<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Auth;

use App\Actions\Auth\GenerateEmailVerifyTokenAction;
use App\Exceptions\Auth\UserHasEmailVeirfyActiveToken;
use App\Exceptions\Auth\UserHasVerifiedEmail;
use Tests\Abstracts\EmailVerifyTestCase;

class GenerateEmailVerifyTokenActionTest extends EmailVerifyTestCase
{
    private GenerateEmailVerifyTokenAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(GenerateEmailVerifyTokenAction::class);
    }

    /** @test */
    public function it_should_throw_UserHasVerifiedEmail_exception_when_given_user_has_activated_account(): void
    {
        $this->expectException(UserHasVerifiedEmail::class);
        $this->expectExceptionMessage('Given user has verified email');

        $this->actionUnderTest->execute($this->user->id);
    }

    /** @test */
    public function it_should_throw_UserHasEmailVerifyActiveToken_exception_when_given_user_has_active_email_verify_token_and_we_try_to_generate_new_token(): void
    {
        $this->markUserAsUnverified();

        $this->expectException(UserHasEmailVeirfyActiveToken::class);
        $this->expectExceptionMessage('Given user has active token');

        $this->actionUnderTest->execute($this->user->id);
    }

    /** @test */
    public function it_should_generate_email_verify_token_for_given_user(): void
    {
        $this->markUserAsUnverified();
        // Truncate default tokens
        $this->clearTable('email_verifies');

        $this->assertDatabaseCount('email_verifies', 0);
        $this->assertFalse($this->user->emailVerifyToken()->exists());

        $createdToken = $this->actionUnderTest->execute($this->user->id);

        $this->assertDatabaseCount('email_verifies', 1);
        $this->assertTrue($this->user->emailVerifyToken()->exists());
        $this->assertEquals($createdToken->id, $this->user->emailVerifyToken->id);
        $this->assertEquals($createdToken->token, $this->user->emailVerifyToken->token);
        $this->assertEquals($createdToken->expire_at, $this->user->emailVerifyToken->expire_at);
        $this->assertEquals($createdToken->created_at, $this->user->emailVerifyToken->created_at);
    }
}
