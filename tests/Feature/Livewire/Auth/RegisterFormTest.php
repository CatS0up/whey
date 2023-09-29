<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Auth;

use App\Enums\HeightUnit;
use App\Enums\PhoneCountry;
use App\Enums\WeightUnit;
use App\Http\Livewire\Auth\RegisterForm;
use App\Notifications\Auth\EmailVerificationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\Concerns\Authentication;
use Tests\Concerns\Media;
use Tests\TestCase;

class RegisterFormTest extends TestCase
{
    use Authentication;
    use Media;
    use RefreshDatabase;

    /** @test */
    public function it_should_register_new_user_and_send_EmailVerificationNotification_when_given_form_is_correct(): void
    {
        Notification::fake();
        $this->clearTable('users');
        /** User from @see Tests\Concerns\Authentication trait*/
        $this->assertDatabaseCount('users', 0);

        Livewire::test(RegisterForm::class)
            ->set('avatar', $this->createTestImage('avatar.png'))
            ->set('name', 'Dummy name')
            ->set('email', 'example@example.com')
            ->set('phone', '+48123456789')
            ->set('phone_country', PhoneCountry::Poland->value)
            ->set('password', 'DummyTestingPassword345%')
            ->set('password_confirmation', 'DummyTestingPassword345%')
            ->set('weight', 70.0)
            ->set('weight_unit', WeightUnit::Kilograms->value)
            ->set('height', 1.75)
            ->set('height_unit', HeightUnit::Meters->value)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertRedirect('/login')
            ->assertSessionHas('success', 'Activation email has been sent');


        $this->assertDatabaseCount('users', 1);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Activation email has been sent', session('success'));

        Notification::assertSentTo(
            notifiable: $this->user,
            notification: EmailVerificationNotification::class,
            callback: fn (mixed $notification, array $channels): bool => in_array('mail', $channels),
        );
    }
}
