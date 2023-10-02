<?php

declare(strict_types=1);

namespace Tests\Feature\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class VerifyEmailTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/dummy-test-route', fn (): string => 'Dummy info from dummy route')->middleware(['web', 'verify_email']);
    }

    /** @test */
    public function it_sould_redirect_auth_user_to_login_page_when_user_has_unactivated_account(): void
    {
        $this->markUserAsUnverified();

        $response = $this->authenticated()
            ->get('/dummy-test-route')
            ->assertRedirect('/login')
            ->assertSessionHas('info', 'You need to confirm your account. We have sent you an activation link, please check your email.');

        $this->followRedirects($response)
            ->assertSeeText('Logowanie')
            ->assertSeeText('You need to confirm your account. We have sent you an activation link, please check your email.');
    }

    /** @test */
    public function it_should_let_auth_user_to_choosen_page_when_given_user_has_activated_account(): void
    {
        $this->authenticated()
            ->get('/dummy-test-route')
            ->assertOk()
            ->assertSeeText('Dummy info from dummy route');
    }
}
