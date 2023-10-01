<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\User;

use App\Actions\User\UpdateUserPasswordAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class UpdateUserPasswordActionTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    private UpdateUserPasswordAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(UpdateUserPasswordAction::class);
    }

    /** @test */
    public function it_should_update_user_password(): void
    {
        $this->assertTrue(Hash::check('password', $this->user->password));

        $actual = $this->actionUnderTest->execute(
            userId: $this->user->id,
            newPassword: 'pwd',
        );

        $this->assertTrue($actual);
        $this->assertTrue(Hash::check('pwd', $this->user->refresh()->password));
    }
}
