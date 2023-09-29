<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Auth;

use App\Actions\Auth\VerifyEmailAction;
use App\Exceptions\Auth\EmailVerifyTokenExpired;
use App\Exceptions\Auth\UserHasVerifiedEmail;
use App\Models\User;
use Tests\Abstracts\EmailVerifyTestCase;

class VerifyEmailActionTest extends EmailVerifyTestCase
{
    private VerifyEmailAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(VerifyEmailAction::class);
    }

    /**
     * @test
     */
    public function it_should_throw_TokenExpired_exception_when_given_token_is_older_than_30_minutes(): void
    {
        $this->expectException(EmailVerifyTokenExpired::class);
        $this->expectExceptionMessage('Email verification token expired');

        $this->makeTokenExpired();

        $this->actionUnderTest->execute($this->token->id);
    }

    /**
     * @test
     */
    public function it_should_throw_UserHasVerifiedEmail_exception_when_given_token_user_has_verified_account(): void
    {
        $this->expectException(UserHasVerifiedEmail::class);
        $this->expectExceptionMessage('Given user has verified email');

        $this->actionUnderTest->execute($this->token->id);
    }

    /** @test */
    public function it_should_verify_email(): void
    {
        // We need user without verified email
        $this->markUserAsUnverified();

        $verifiedToken = $this->actionUnderTest->execute($this->token->id);

        $this->assertTrue($verifiedToken->user->hasVerifiedEmail());
    }
}
