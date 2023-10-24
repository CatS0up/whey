<?php

declare(strict_types=1);

namespace Tests\Feature\Web\Auth;

use App\Notifications\Auth\EmailVerificationNotification;
use Illuminate\Support\Facades\Notification;
use Tests\Abstracts\EmailVerifyTestCase;

class EmailVerifyResendTest extends EmailVerifyTestCase
{
    /** @test */
    public function auth_user_can_not_display_resend_email_verify_token_page_and_should_be_redirect_to_home_page_for_auth_users(): void
    {
        $this->authenticated()
            ->get("/email-verification/{$this->token->token}/resend")
            ->assertDontSeeText('Link aktywacyjny wygasł')
            ->assertRedirect('/dashboard');
    }

    /** @test */
    public function it_should_redirect_to_login_page_when_guest_user_try_to_display_email_verify_token_page_when_token_owner_has_activated_account(): void
    {
        $response = $this->get("/email-verification/{$this->token->token}/resend")
            ->assertRedirect("/login")
            ->assertSessionHas('info', 'User has verified email');

        $this->followRedirects($response)
            ->assertSeeText('Logowanie')
            ->assertSeeText('User has verified email');
    }

    /** @test */
    public function it_should_redirect_to_login_page_when_guest_user_try_to_display_email_verify_token_page_when_token_owner_has_active_email_verify_token(): void
    {
        $this->markUserAsUnverified();

        $response = $this->get("/email-verification/{$this->token->token}/resend")
            ->assertRedirect('/login')
            ->assertSessionHas('info', 'User has active email verification token');

        $this->followRedirects($response)
            ->assertSeeText('Logowanie')
            ->assertSeeText('User has active email verification token');
    }

    /** @test */
    public function guest_user_can_display_email_verify_token_page_when_given_token_is_expired_and_owner_has_not_activated_account(): void
    {
        $this->markUserAsUnverified();
        $this->makeTokenExpired();

        $this->get("/email-verification/{$this->token->token}/resend")
            ->assertSeeText('Link aktywacyjny wygasł');
    }

    /** @test */
    public function auth_user_can_not_resend_email_verify_token_and_should_be_redirect_to_home_page_for_auth_users(): void
    {
        $this->authenticated()
            ->post("/email-verification/{$this->token->token}/resend")
            ->assertRedirect('/dashboard');
    }


    /** @test */
    public function it_should_redirect_to_login_page_when_guest_user_try_to_resend_email_verify_token_when_token_owner_has_activated_account(): void
    {
        $this->makeTokenExpired();

        $response = $this->post("/email-verification/{$this->token->token}/resend")
            ->assertRedirect('/login')
            ->assertSessionHas('info', 'User has verified email');

        $this->followRedirects($response)
            ->assertSeeText('Logowanie')
            ->assertSeeText('User has verified email');
    }

    /** @test */
    public function it_should_resend_email_verify_token_and_redirect_to_login_page_after_that_when_guest_user_has_expired_token_and_does_not_has_activated_account(): void
    {
        Notification::fake();
        $this->markUserAsUnverified();
        $this->makeTokenExpired();

        $response = $this->post("/email-verification/{$this->token->token}/resend")
            ->assertRedirect('/login')
            ->assertSessionHas('success', 'The verification link has been sent');

        $this->followRedirects($response)
            ->assertSeeText('Logowanie')
            ->assertSeeText('The verification link has been sent');

        Notification::assertSentTo(
            notifiable: $this->user,
            notification: EmailVerificationNotification::class,
            callback: fn (mixed $notification, array $channels): bool => in_array('mail', $channels),
        );
    }
}
