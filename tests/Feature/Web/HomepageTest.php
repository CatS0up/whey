<?php

declare(strict_types=1);

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    /** @test */
    public function guest_user_should_be_redirected_to_the_login_page_when_their_open_home_page(): void
    {
        $response = $this->get('/')
            ->assertRedirect('/login');

        $this->followRedirects($response)
            ->assertSeeText('Logowanie');
    }

    /** @test */
    public function auth_user_should_be_redirected_to_the_dashboard_page_when_their_open_home_page(): void
    {
        $response = $this->authenticated()
            ->get('/')
            ->assertRedirect('/dashboard');

        $this->followRedirects($response)
            ->assertSeeText($this->user->name);
    }
}
