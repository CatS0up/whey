<?php

declare(strict_types=1);

namespace Tests\Feature\Middleware;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/dummy-test-route', fn (): string => 'Dummy info from dummy route')->middleware(['web', 'role:dummy-role']);
    }

    /** @test */
    public function it_should_abort_request_with_401_status_code_when_Role_middleware_has_been_called_by_guest_user(): void
    {
        $this->get('/dummy-test-route')
            ->assertDontSeeText('Dummy info from dummy route')
            ->assertUnauthorized();
    }

    /** @test */
    public function it_should_abort_request_with_403_status_code_when_auth_user_does_not_have_given_role(): void
    {
        $this->authenticated()
            ->get('/dummy-test-route')
            ->assertDontSeeText('Dummy info from dummy route')
            ->assertForbidden();
    }

    /** @test */
    public function it_should_display_page_protected_by_Role_middleware_when_auth_user_has_given_role(): void
    {
        $this->user->roles()
            ->save(Role::factory()->create(['name' => 'Dummy role',]));

        $this->authenticated()
            ->get('/dummy-test-route')
            ->assertSeeText('Dummy info from dummy route')
            ->assertOk();
    }
}
