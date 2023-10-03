<?php

declare(strict_types=1);

namespace Tests\Feature\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class RedirectIfHasTemporaryPasswordTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/dummy-test-route', fn (): string => 'Dummy info from dummy route')->middleware(['web', 'reset_password']);
    }

    /** @test */
    public function it_should_redirect_auth_user_to_reset_password_page_when_user_has_temporary_password(): void
    {
        $this->user->markPasswordAsTemporary();

        $response = $this->authenticated()
            ->get('/dummy-test-route')
            ->assertDontSeeText('Dummy info from dummy route')
            ->assertRedirect('/temporary-password/reset')
            ->assertSessionHas('warning', 'You have a temporary password. If you want to continue, you should set a new password');

        $this->followRedirects($response)
            ->assertSeeText('You have a temporary password. If you want to continue, you should set a new password')
            ->assertSeeText('Reset hasła');
    }

    /** @test */
    public function it_should_let_auth_user_to_choosen_page_when_user_has_no_temporary_password(): void
    {
        $this->authenticated()
            ->get('/dummy-test-route')
            ->assertOk()
            ->assertSeeText('Dummy info from dummy route');
    }
}
