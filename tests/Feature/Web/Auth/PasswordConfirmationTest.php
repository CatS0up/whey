<?php

declare(strict_types=1);

namespace Tests\Feature\Web\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/dummy-test-route', fn (): string => 'Dummy info from dummy route')->middleware(['web', 'confirm_password']);
    }

    public function getUserFactoryAttributes(): array
    {
        return [
            'password' => 'dummy_password',
        ];
    }

    /** @test */
    public function guest_user_can_not_display_password_confirmation_page_and_should_be_redirected_to_the_login_page(): void
    {
        $response = $this->get('/confirm-password')
            ->assertRedirect('/login')
            ->assertDontSeeText('Potwierdź hasło');

        $this->followRedirects($response)
            ->assertSeeText('Logowanie');
    }

    /** @test */
    public function auth_user_can_display_password_confirmation_page(): void
    {
        $this->authenticated()
            ->get('/confirm-password')
            ->assertOk()
            ->assertSeeText('Potwierdź hasło');
    }

    /** @test */
    public function guest_user_should_be_redirected_to_the_login_page_when_they_attempt_to_confirm_their_password(): void
    {
        $response = $this->post(
            uri: '/confirm-password',
            data: [
                'password' => 'dummy_password',
            ],
        )
            ->assertRedirect('/login')
            ->assertDontSeeText('Potwierdź hasło');

        $this->followRedirects($response)
            ->assertSeeText('Logowanie');
    }

    /** @test */
    public function auth_user_should_be_redirected_back_to_the_password_confirmation_page_with_errors_when_given_password_is_incorrect_for_them(): void
    {
        $response = $this->authenticated()
            ->post(
                uri: '/confirm-password',
                data: [
                    'password' => 'incorrect password',
                ],
            )
            ->assertRedirect('/confirm-password')
            ->assertSessionHas('error', 'The provided password does not match our records');

        $this->followRedirects($response)
            ->assertSeeText('The provided password does not match our records')
            ->assertSeeText('Potwierdź hasło');
    }

    /** @test */
    public function auth_user_should_be_redirect_to_the_protected_by_password_confirmation_page_when_given_password_is_correct_for_them(): void
    {
        $this->setDummyRouteUrlAsPasswordConfirmationDestinationPath();

        $response = $this->authenticated()
            ->post(
                uri: '/confirm-password',
                data: [
                    'password' => 'dummy_password',
                ],
            )
            ->assertRedirect('/dummy-test-route');

        $this->followRedirects($response)
            ->assertSeeText('Dummy info from dummy route');
    }

    /** @test */
    public function it_should_not_forget_the_password_confirmation_destination_path_session_key_after_a_failed_password_confirmation_by_the_auth_user(): void
    {
        $this->setDummyRouteUrlAsPasswordConfirmationDestinationPath();

        $this->authenticated()
            ->post(
                uri: '/confirm-password',
                data: [
                    'password' => 'invalid password',
                ],
            );

        $this->assertTrue(session()->has('password_confirmation_destination_path'));
    }

    /** @test */
    public function it_should_forget_the_password_confirmation_destination_path_session_key_after_a_successful_password_confirmation_by_the_auth_user(): void
    {
        $this->setDummyRouteUrlAsPasswordConfirmationDestinationPath();

        $this->assertTrue(session()->has('password_confirmation_destination_path'));

        $this->authenticated()
            ->post(
                uri: '/confirm-password',
                data: [
                    'password' => 'dummy_password',
                ],
            );

        $this->assertFalse(session()->has('password_confirmation_destination_path'));
    }

    private function setDummyRouteUrlAsPasswordConfirmationDestinationPath(): void
    {
        session()->put('password_confirmation_destination_path', 'dummy-test-route');
    }
}
