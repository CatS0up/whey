<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Auth;

use App\Actions\Auth\ResetTemporaryPasswordAction;
use App\DataObjects\User\UpdatePasswordData;
use App\Exceptions\Auth\UserHasNoTemporaryPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class ResetTemporaryPasswordActionTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    private ResetTemporaryPasswordAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(ResetTemporaryPasswordAction::class);
    }

    /** @test */
    public function it_should_throw_UserHasNoTemporaryPassword_exception_when_given_user_does_not_have_temporary_password(): void
    {
        $this->expectException(UserHasNoTemporaryPassword::class);
        $this->expectExceptionMessage('Given user has no temporary password assigned');

        $this->actionUnderTest->execute(
            UpdatePasswordData::from([
                'userId' => $this->user->id,
                'password' => 'new_password',
            ]),
        );
    }

    /** @test */
    public function it_should_update_temporary_user_password_when_given_user_has_temporary_password(): void
    {
        $this->user->markPasswordAsTemporary();

        $this->assertTrue(Hash::check('password', $this->user->password));

        $this->actionUnderTest->execute(
            UpdatePasswordData::from([
                'userId' => $this->user->id,
                'password' => 'new_password',
            ]),
        );

        $this->refreshUser();
        $this->assertTrue(Hash::check('new_password', $this->user->password));
    }

    /** @test */
    public function it_should_set_user_password_as_not_temporary_when_temporary_password_will_be_changed(): void
    {
        $this->user->markPasswordAsTemporary();

        $this->assertTrue($this->user->hasTempPassword());

        $this->actionUnderTest->execute(
            UpdatePasswordData::from([
                'userId' => $this->user->id,
                'password' => 'new_password',
            ]),
        );

        $this->refreshUser();
        $this->assertFalse($this->user->hasTempPassword());
    }
}
