<?php

declare(strict_types=1);

namespace Tests\Feature\Web\Auth;

use Tests\Abstracts\EmailVerifyTestCase;

class EmailVerifyTest extends EmailVerifyTestCase
{
    /** @test */
    public function auth_user_can_not_verify_email_and_should_be_redirect_to_home_page_for_auth_users(): void
    {
        $this->authenticated()
            ->post("/email-verification/{$this->token->token}")
            ->assertRedirect('/dashboard');
    }

    /** @test */
    public function it_should_redirect_to_email_verify_token_resend_page_when_guest_user_try_to_activate_email_by_expired_token(): void
    {
        $this->markUserAsUnverified();
        $this->makeTokenExpired();

        $response = $this->post("/email-verification/{$this->token->token}")
            ->assertRedirect("/email-verification/{$this->token->token}/resend")
            ->assertSessionHas('warning', 'Given token has been expired');

        $this->followRedirects($response)
            ->assertSeeText('Link aktywacyjny wygasł')
            ->assertSeeText('Given token has been expired');
    }

    /** @test */
    public function it_should_redirect_to_login_page_when_guest_user_try_to_activate_email_by_token_which_belongs_to_activated_user(): void
    {
        $response = $this->post("/email-verification/{$this->token->token}")
            ->assertRedirect('/login')
            ->assertSessionHas('info', 'User has verified email');

        $this->followRedirects($response)
            ->assertSeeText('Logowanie')
            ->assertSeeText('User has verified email');
    }

    /** @test */
    public function it_should_verify_user_email_and_redirect_guest_user_to_login_page_when_guest_user_use_active_token_and_token_owner_has_not_activated_account(): void
    {
        $this->markUserAsUnverified();

        $response = $this->post("/email-verification/{$this->token->token}")
            ->assertRedirect('/login')
            ->assertSessionHas('success', 'Your email has been verified :)');

        $this->followRedirects($response)
            ->assertSeeText('Logowanie')
            ->assertSeeText('Your email has been verified :)');
    }
}
