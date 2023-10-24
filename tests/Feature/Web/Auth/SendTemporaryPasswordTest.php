<?php

declare(strict_types=1);

namespace Tests\Feature\Web\Auth;

use App\Notifications\Auth\UserTemporaryPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class SendTemporaryPasswordTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    /** @test */
    public function it_should_redirect_user_to_auth_users_home_page_when_auth_user_try_to_display_send_temporary_password_page(): void
    {
        $this->authenticated()
            ->get('/temporary-password/send')
            ->assertRedirect('/dashboard');
    }

    /** @test */
    public function guest_user_can_display_send_temporary_password_page(): void
    {
        $this->get('/temporary-password/send')
            ->assertOk()
            ->assertSeeText('Wyślij link do resetu hasła');
    }

    /** @test */
    public function it_should_redirect_user_to_auth_users_home_page_when_auth_user_try_to_send_temporary_password(): void
    {
        $this->authenticated()
            ->post(
                uri: '/temporary-password/send',
                data: [
                    'email' => $this->user->email,
                ],
            )
            ->assertDontSeeText('Wyślij link do resetu hasła')
            ->assertRedirect('/dashboard');
    }

    /** @test */
    public function guest_user_can_send_temporary_password_when_given_email_is_correct_and_redirect_user_to_login_page(): void
    {
        Notification::fake();

        $response = $this->post(
            uri: '/temporary-password/send',
            data: [
                'email' => $this->user->email,
            ],
        )
            ->assertRedirect('/login')
            ->assertSessionHas('success', 'Temporary password has been sent');

        $this->followRedirects($response)
            ->assertSeeText('Temporary password has been sent')
            ->assertSeeText('Logowanie');

        Notification::assertSentTo(
            notifiable: $this->user,
            notification: UserTemporaryPasswordNotification::class,
            callback: fn (mixed $notification, array $channels): bool => in_array('mail', $channels),
        );
    }
}
