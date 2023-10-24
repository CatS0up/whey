<?php

declare(strict_types=1);

namespace Tests\Feature\Web\Auth;

use App\Actions\Auth\LoginAsDemoUserAction;
use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\Mock;
use Tests\Abstracts\LoginAsDemoUserTestCase;
use Tests\Concerns\Authentication;

class LoginAsDemoUserTest extends LoginAsDemoUserTestCase
{
    use Authentication;
    use RefreshDatabase;

    /** @test */
    public function auth_user_can_not_display_the_choose_demo_user_page_and_should_be_redirect_to_home_page_for_auth_users(): void
    {
        $this->authenticated()
            ->get('/demo-user')
            ->assertRedirect('/dashboard');
    }

    /** @test */
    public function it_should_abort_request_with_404_code_when_guest_user_try_to_display_the_choose_demo_user_page_but_demo_users_are_disabled(): void
    {
        $this->disableDemoUsersInConfig();

        $this->get('/demo-user')
            ->assertNotFound();
    }

    /** @test */
    public function guest_user_can_display_the_the_choose_demo_user_page_when_demo_users_are_enabled(): void
    {
        $this->enableDemoUsersInConfig();

        $this->get('/demo-user')
            ->assertOk()
            ->assertSeeText('Choose your fighter');
    }

    /** @test */
    public function auth_user_should_be_redirect_to_home_page_for_auth_users_when_their_try_to_log_in_as_demo_user_and_demo_users_are_enabled(): void
    {
        $this->enableDemoUsersInConfig();
        $this->runPermissionSeeders();

        $this->authenticated()
            ->post('/demo-user/user')
            ->assertRedirect('/dashboard');
    }

    /** @test */
    public function it_should_abort_request_with_404_code_when_guest_user_try_to_login_as_demo_user_page_but_demo_users_are_disabled(): void
    {
        $this->enableDemoUsersInConfig();

        $this->post('/demo-user')
            ->assertOk()
            ->assertSeeText('Choose your fighter');
    }

    /** @test */
    public function guest_should_be_redirected_back_when_their_try_to_login_as_demo_user_been_failed_and_demo_users_are_enabled(): void
    {
        $this->enableDemoUsersInConfig();
        $this->runPermissionSeeders();

        /** @var Mock|LoginAsDemoUserAction */
        $mock = mock(LoginAsDemoUserAction::class);
        $mock->expects('execute')
            ->withArgs(function (Role $role): bool {
                $this->assertEquals(Role::User, $role);

                return true;
            })
            ->once()
            ->andReturnFalse();

        app()->instance(LoginAsDemoUserAction::class, $mock);

        $response = $this
            ->from('/demo-user')
            ->post('/demo-user/user')
            ->assertRedirect('/demo-user')
            ->assertSessionHas('error', 'Unknown error');

        $this->followRedirects($response)
            ->assertSeeText('Choose your fighter')
            ->assertSeeText('Unknown error');
    }

    /** @test */
    public function it_should_log_in_guest_user_as_demo_user_when_demo_users_are_enabled(): void
    {
        $this->enableDemoUsersInConfig();
        $this->runPermissionSeeders();

        $response = $this->post('/demo-user/user')
            ->assertRedirect('/dashboard')
            ->assertSessionHas('success', 'You are logged in as user');

        $this->followRedirects($response)
            ->assertSeeText('You are logged in as user');
    }
}
