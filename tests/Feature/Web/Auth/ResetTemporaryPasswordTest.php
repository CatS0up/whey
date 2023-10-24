<?php

declare(strict_types=1);

namespace Tests\Feature\Web\Auth;

use App\Actions\Auth\ResetTemporaryPasswordAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Mockery;
use Mockery\Mock;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class ResetTemporaryPasswordTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mockery::globalHelpers();

        Route::get('/dummy-test-route', fn (): string => 'Dummy info from dummy route')->middleware(['web']);
    }

    /** @test */
    public function guest_user_can_not_display_reset_temporary_password_page_and_should_be_redirected_to_the_login_page(): void
    {
        $response = $this->get('temporary-password/reset')
            ->assertDontSeeText('Reset hasła')
            ->assertRedirect('/login');

        $this->followRedirects($response)
            ->assertSeeText('Logowanie');
    }

    /** @test */
    public function auth_user_can_not_display_reset_temporary_password_page_and_should_be_redirected_back_when_they_do_not_have_a_temporary_password(): void
    {
        $response = $this->authenticated()
            ->from('/dummy-test-route')
            ->get('temporary-password/reset')
            ->assertDontSeeText('Reset hasła')
            ->assertRedirect('/dummy-test-route')
            ->assertSessionHas('error', 'User has no temporary password assigned'); // Redirect back

        $this->followRedirects($response)
            ->assertSeeText('Dummy info from dummy route');
    }

    /** @test */
    public function auth_user_can_display_reset_temporary_password_page_when_they_do_not_have_a_temporary_password(): void
    {
        $this->user->markPasswordAsTemporary();

        $this->authenticated()
            ->get('temporary-password/reset')
            ->assertSeeText('Reset hasła')
            ->assertOk();
    }

    /** @test */
    public function guest_user_can_not_reset_temporary_password_and_should_be_redirected_to_login_page(): void
    {
        $this->post(
            uri: 'temporary-password/reset',
            data: [
                'password' => 'NewCorrectPassword!2345',
                'password_confirmation' => 'NewCorrectPassword!2345',
            ],
        )
            ->assertRedirect('/login')
            ->assertDontSeeText('Reset hasła');
    }

    /** @test */
    public function auth_user_can_not_reset_temporary_password_and_should_be_redirect_back_when_user_does_not_have_a_temporary_password(): void
    {
        $response = $this->authenticated()
            ->from('/dummy-test-route')
            ->post(
                uri: 'temporary-password/reset',
                data: [
                    'password' => 'NewCorrectPassword!2345',
                    'password_confirmation' => 'NewCorrectPassword!2345',
                ],
            )
            ->assertDontSeeText('Reset hasła')
            ->assertRedirect('/dummy-test-route')
            ->assertSessionHas('error', 'User has no temporary password assigned'); // Redirect back

        $this->followRedirects($response)
            ->assertSeeText('Dummy info from dummy route');
    }

    /** @test */
    public function auth_user_should_be_redirect_to_previous_page_when_reset_temporary_password_has_been_failed(): void
    {
        $this->user->markPasswordAsTemporary();

        /** @var Mock|ResetTemporaryPasswordAction $mock */
        $mock = mock(ResetTemporaryPasswordAction::class)->makePartial();
        $mock->expects('execute')
            ->withAnyArgs()
            ->once()
            ->andReturnFalse();

        app()->instance(ResetTemporaryPasswordAction::class, $mock);

        $response = $this->authenticated()
            ->from('temporary-password/reset') // form page
            ->post(
                uri: 'temporary-password/reset',
                data: [
                    'password' => 'NewCorrectPassword!2345',
                    'password_confirmation' => 'NewCorrectPassword!2345',
                ],
            )
            ->assertRedirect('temporary-password/reset')
            ->assertSessionHas('error', 'An unknown error occurred during the password reset'); // Redirect back

        $this->followRedirects($response)
            ->assertSeeText('An unknown error occurred during the password reset');
    }

    /** @test */
    public function auth_user_can_reset_temporary_password_and_should_be_redirected_to_the_auth_user_home_page_when_they_have_a_temporary_password(): void
    {
        $this->user->markPasswordAsTemporary();

        $response = $this->authenticated()
            ->post(
                uri: 'temporary-password/reset',
                data: [
                    'password' => 'NewCorrectPassword!2345',
                    'password_confirmation' => 'NewCorrectPassword!2345',
                ],
            )
            ->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Your password has been reset. You can sign up now :)');


        $this->followRedirects($response)
            ->assertSeeText('Your password has been reset. You can sign up now :)');
    }
}
