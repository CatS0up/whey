<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Exercise;

use App\Actions\Exercise\SendVerificationEmailAction;
use App\Exceptions\Authorization\AuthorizedUsersNotFound;
use App\Models\Exercise;
use App\Models\Permission;
use App\Models\User;
use App\Notifications\Exercise\ExerciseVerificationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\Concerns\Authentication;
use Tests\Concerns\Authorization;
use Tests\TestCase;

class SendVerificationEmailActionTest extends TestCase
{
    use Authentication;
    use Authorization;
    use RefreshDatabase;

    private SendVerificationEmailAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(SendVerificationEmailAction::class);
    }

    /** @test */
    public function it_should_throw_AuthorizedUsersNotFound_exception_when_there_are_no_users_in_the_system_authorized_to_verify_exercises(): void
    {
        $this->expectException(AuthorizedUsersNotFound::class);
        $this->expectExceptionMessage('In your system, there are no users who have the \'verify-exercises\' permission');

        $exercise = Exercise::factory()->for($this->user, 'author')->create();
        $this->actionUnderTest->execute($exercise->id);
    }

    /** @test */
    public function it_should_send_ExerciseVerificationNotification_to_the_users_authorized_to_verify_exercises(): void
    {
        Notification::fake();

        $exercise = Exercise::factory()->for($this->user, 'author')->create();
        $permission = Permission::factory()->create(['name' => 'verify-exercises']);
        $recipients = User::factory(3)->create();
        $unauthorizedUsers = User::factory(3)->create();

        foreach ($recipients as $recipient) {
            $this->assignPermissionToUser(
                user: $recipient,
                permission: $permission,
            );
        }

        $this->actionUnderTest->execute($exercise->id);

        Notification::assertSentTo(
            notifiable: $recipients,
            notification: ExerciseVerificationNotification::class,
            callback: fn (mixed $notification, array $channels): bool => in_array('mail', $channels),
        );

        Notification::assertNotSentTo(
            $unauthorizedUsers,
            ExerciseVerificationNotification::class,
        );
    }
}
