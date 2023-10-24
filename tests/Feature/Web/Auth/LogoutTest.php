<?php

declare(strict_types=1);

namespace Tests\Feature\Web\Auth;

use App\Actions\Auth\LogoutAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\Mock;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mockery::globalHelpers();
    }

    /** @test */
    public function guest_user_can_not_log_out_and_should_be_redirect_to_login_page(): void
    {
        $response = $this->post('/logout')
            ->assertRedirect('/login');

        $this->followRedirects($response)
            ->assertDontSeeText('You are logged out');
    }

    /** @test */
    public function auth_user_should_be_redirect_to_previous_page_when_loged_out_has_been_failed(): void
    {
        /** @var Mock|LogoutAction $mock */
        $mock = mock(LogoutAction::class)->makePartial();
        $mock->expects('execute')->once()->andReturnFalse();

        app()->instance(LogoutAction::class, $mock);

        $response = $this->authenticated()
            ->from('/dashboard')
            ->post('/logout')
            ->assertRedirect('/dashboard')
            ->assertSessionHas('error', 'Logout failed');

        $this->followRedirects($response)
            ->assertSeeText('Logout failed');
    }

    /** @test */
    public function auth_user_can_logout_and_should_be_redirect_to_login_page(): void
    {
        $response = $this->authenticated()
            ->post('/logout')
            ->assertRedirect('/login')
            ->assertSessionHas('success', 'You are logged out');

        $this->followRedirects($response)
            ->assertSeeText('You are logged out');
    }
}
