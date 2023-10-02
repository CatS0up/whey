<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Auth;

use App\Actions\Auth\GenerateTemporaryPasswordAction;
use App\Actions\Auth\SendTemporaryPasswordAction;
use App\Notifications\Auth\UserTemporaryPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Mockery;
use Mockery\Mock;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class SendTemporaryPasswordActionTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    private Mock|GenerateTemporaryPasswordAction $generateTemporaryPasswordActionMock;
    private SendTemporaryPasswordAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        Mockery::globalHelpers();

        $this->generateTemporaryPasswordActionMock = mock(GenerateTemporaryPasswordAction::class);
        app()->instance(GenerateTemporaryPasswordAction::class, $this->generateTemporaryPasswordActionMock);
        $this->actionUnderTest = app()->make(SendTemporaryPasswordAction::class);
    }

    /** @test */
    public function it_should_send_UserTemporaryPasswordNotification_with_generated_temporary_password_to_choosen_user(): void
    {
        Notification::fake();

        $this->generateTemporaryPasswordActionMock
            ->shouldReceive('execute')
            ->with($this->user->id)
            ->once();

        $this->actionUnderTest = app()->make(SendTemporaryPasswordAction::class);

        $this->actionUnderTest->execute($this->user->id);

        Notification::assertSentTo(
            notifiable: $this->user,
            notification: UserTemporaryPasswordNotification::class,
            callback: fn (mixed $notification, array $channels): bool => in_array('mail', $channels),
        );
    }
}
