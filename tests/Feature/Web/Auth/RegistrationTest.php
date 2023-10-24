<?php

declare(strict_types=1);

namespace Tests\Feature\Web\Auth;

use App\Http\Livewire\Auth\RegisterForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    /** @test */
    public function auth_user_can_not_display_register_page_and_should_be_redirect_to_home_page_for_auth_users(): void
    {
        $this->authenticated()
            ->get('/register')
            ->assertDontSeeText('Rejestracja')
            ->assertRedirect('/dashboard');
    }

    /** @test */
    public function guest_user_can_display_register_page(): void
    {
        $this->get('/register')
            ->assertOk()
            ->assertSeeText('Rejestracja');
    }

    /** @test */
    public function register_page_should_contain_register_form_livewire_component(): void
    {
        $this->get('/register')
            ->assertSeeLivewire(RegisterForm::class);
    }
}
