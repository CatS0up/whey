<?php

declare(strict_types=1);

namespace Tests\Feature\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class ConfirmPasswordTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/dummy-test-route', fn (): string => 'Dummy info from dummy route')->middleware(['web', 'confirm_password']);
    }

    /** @test */
    public function it_should_abort_request_with_401_status_code_when_ConfirmPassword_middleware_has_been_called_by_guest_user(): void
    {
        $this->get('/dummy-test-route')
            ->assertDontSeeText('Dummy info from dummy route')
            ->assertUnauthorized();
    }

    /** @test */
    public function it_should_redirect_the_auth_user_to_the_password_confirmation_page_when_given_route_is_protected_by_the_ConfirmPassword_middleware(): void
    {
        $response = $this->authenticated()
            ->get('/dummy-test-route')
            ->assertRedirect('/confirm-password');

        $this->followRedirects($response)
            ->assertSeeText('Potwierdź hasło');
    }

    /** @test */
    public function it_should_set_a_session_value_with_destination_path_for_auth_user_where_the_user_should_be_redirected_after_password_confirmation(): void
    {
        $this->authenticated()
            ->get('/dummy-test-route')
            ->assertSessionHas('password_confirmation_destination_path', url('dummy-test-route'));
    }

    /** @test */
    public function it_should_not_redirected_the_auth_user_to_the_password_confirmation_page_when_given_route_is_protected_by_the_ConfirmPassword_middleware_and_password_timeout_has_not_passed(): void
    {
        session()->put('auth.password_confirmed_at', time());

        $response = $this->authenticated()
            ->get('/dummy-test-route')
            ->assertOk();

        $this->followRedirects($response)
            ->assertSeeText('Dummy info from dummy route');
    }

    /** @test */
    public function it_should_redirect_the_auth_user_to_the_password_confirmation_page_when_given_route_is_protected_by_the_ConfirmPassword_middleware_and_password_timeout_has_passed(): void
    {
        session()->put('auth.password_confirmed_at', time());

        $response = $this->authenticated()
            ->get('/dummy-test-route')
            ->assertOk();

        $this->followRedirects($response)
            ->assertSeeText('Dummy info from dummy route');


        // The password timeout is set to 1800 seconds (default)
        $this->travel(1800)->seconds();

        $response = $this->authenticated()
            ->get('/dummy-test-route')
            ->assertRedirect('/confirm-password');

        $this->followRedirects($response)
            ->assertSeeText('Potwierdź hasło');
    }
}
