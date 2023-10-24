<?php

declare(strict_types=1);

namespace Tests\Feature\Web\Auth;

use App\Actions\Auth\LoginAction;
use App\DataObjects\Auth\LoginData;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Mockery;
use Mockery\Mock;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class LoginTest extends TestCase
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
    public function auth_user_can_not_display_login_page_and_should_be_redirect_to_home_page_for_auth_users(): void
    {
        $this->authenticated()
            ->get('/login')
            ->assertRedirect('/dashboard');
    }

    /** @test */
    public function guest_user_can_display_login_page_and_should_be_redirect_to_login_page(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSeeText('Logowanie');
    }

    /** @test */
    public function auth_user_can_not_sign_in_and_should_be_redirect_to_home_page_for_auth_users(): void
    {
        $otherUser = User::factory()
            ->create([
                'email' => 'dummy@email.com',
                'password' => 'test',
            ]);

        $response = $this->authenticated()
            ->post(
                uri: '/login',
                data: [
                    'login' => 'dummy@email.com',
                    'password' => 'test',
                ],
            )
            ->assertRedirect('/dashboard');

        $this->followRedirects($response)
            ->assertDontSeeText('Login has been succesful')
            ->assertDontSeeText('Invalid credentials');

        $this->assertAuthenticatedAs($this->user);
        $this->assertNotEquals(auth()->id(), $otherUser->id);
    }

    /** @test */
    public function guest_user_can_not_sign_in_with_incorrect_credentials_and_should_be_redirect_back_to_login_page(): void
    {
        $response = $this->post(
            uri: '/login',
            data: [
                'login' => 'imdoesnotexists@email.com',
                'password' => 'invalidpassword',
            ],
        )
            ->assertRedirect('/login')
            ->assertSessionHas('error', 'Invalid credentials');

        $this->followRedirects($response)
            ->assertSeeText('Logowanie')
            ->assertSeeText('Invalid credentials');
    }

    /** @test */
    public function guest_user_can_sign_in_with_correct_credentials_and_should_be_redirect_to_home_page_for_auth_users(): void
    {
        $response = $this->post(
            uri: '/login',
            data: [
                'login' => $this->user->email,
                'password' => 'password',
            ],
        )
            ->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'Login has been succesful');

        $this->followRedirects($response)
            ->assertSeeText('Login has been succesful');

        $this->assertAuthenticatedAs($this->user);
    }

    /** @test */
    public function guest_should_be_redirect_back_to_login_page_when_login_attempt_has_been_failed(): void
    {
        /** @var Mock|LoginAction */
        $mock = mock(LoginAction::class);
        $mock->expects('execute')
            ->withArgs(function (LoginData $data): bool {
                $this->assertEquals($this->user->email, $data->login);
                $this->assertEquals('password', $data->password);

                return true;
            })
            ->once()
            ->andReturnFalse();

        app()->instance(LoginAction::class, $mock);

        $response = $this->post(
            uri: '/login',
            data: [
                'login' => $this->user->email,
                'password' => 'password',
            ],
        )
            ->assertRedirect('/login')
            ->assertSessionHas('error', 'Invalid credentials');

        $this->followRedirects($response)
            ->assertSeeText('Logowanie')
            ->assertSeeText('Invalid credentials');
    }

    /** @test */
    public function it_should_remember_user_when_user_check_remember_me_checkbox_during_sign_in(): void
    {
        $rememberMeCookieValue = vsprintf(
            format: '%s|%s|%s',
            values: [
                $this->user->id,
                $this->user->getRememberToken(),
                $this->user->password,
            ],
        );

        $this->post(
            uri: '/login',
            data: [
                'login' => $this->user->email,
                'password' => 'password',
                'remember_me' => 'on',
            ],
        )
            ->assertRedirect('/dashboard')
            ->assertCookie(
                cookieName: auth()->getRecallerName(),
                value: $rememberMeCookieValue,
            );

        $this->assertAuthenticatedAs($this->user);

        $this->get('/dummy-test-route')
            ->assertCookie(
                cookieName: auth()->getRecallerName(),
                value: $rememberMeCookieValue,
            );

        $this->assertAuthenticatedAs($this->user);
    }
}
