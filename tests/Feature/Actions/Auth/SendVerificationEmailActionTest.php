<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Auth;

use App\Actions\Auth\SendVerificationEmailAction;
use App\Notifications\Auth\EmailVerificationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class SendVerificationEmailActionTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    private SendVerificationEmailAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(SendVerificationEmailAction::class);
    }

    /** @test */
    public function it_should_generate_email_verify_token_and_send_EmailVerificationNotification_to_given_user(): void
    {
        Notification::fake();
        $this->markUserAsUnverified();

        $this->assertDatabaseCount('email_verifies', 0);

        $this->actionUnderTest->execute($this->user->id);

        $this->assertDatabaseCount('email_verifies', 1);

        Notification::assertSentTo(
            notifiable: $this->user,
            notification: EmailVerificationNotification::class,
            callback: fn (mixed $notification, array $channels): bool => in_array('mail', $channels),
        );
    }
}
