<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Auth;

use App\Enums\HeightUnit;
use App\Enums\PhoneCountry;
use App\Enums\WeightUnit;
use App\Http\Livewire\Auth\RegisterForm;
use App\Models\User;
use App\Notifications\Auth\EmailVerificationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\Concerns\Media;
use Tests\Concerns\SkipReCaptchaValidation;
use Tests\TestCase;

class RegisterFormTest extends TestCase
{
    use Media;
    use RefreshDatabase;
    use SkipReCaptchaValidation;

    /** @test */
    public function it_can_not_register_new_user_when_form_contain_validation_errors(): void
    {
        $this->assertDatabaseCount('users', 0);

        Livewire::test(RegisterForm::class)
            ->set('avatar', $this->createTestImage('avatar.png'))
            ->set('name', 'Dummy name')
            ->set('email', "I'M INCORRECT EMAIL") // <--
            ->set('phone', '+48123456789')
            ->set('phone_country', PhoneCountry::Poland->value)
            ->set('password', 'DummyTestingPassword345%')
            ->set('password_confirmation', 'DummyTestingPassword345%')
            ->set('weight', 70.0)
            ->set('weight_unit', WeightUnit::Kilograms->value)
            ->set('height', 1.75)
            ->set('height_unit', HeightUnit::Meters->value)
            ->emit('recaptcha-ready') // This event is triggered when reCAPTCHA is ready, after form submission
            ->assertHasErrors('email');

        $this->assertDatabaseCount('users', 0); // User was'nt created
    }

    /** @test */
    public function it_should_register_new_user_when_form_is_correct(): void
    {
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
            ->emit('recaptcha-ready') // This event is triggered when reCAPTCHA is ready, after form submission
            ->assertHasNoErrors()
            ->assertRedirect('/login')
            ->assertSessionHas('success', 'Activation email has been sent');

        $this->assertDatabaseCount('users', 1);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Activation email has been sent', session('success'));
    }

    /** @test */
    public function it_should_send_EmailVerificationNotification_when_user_has_been_succesfull_create(): void
    {
        Notification::fake();

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
            ->emit('recaptcha-ready'); // This event is triggered when reCAPTCHA is ready, after form submission

        $this->assertDatabaseCount('users', 1);

        Notification::assertSentTo(
            notifiable: User::first(),
            notification: EmailVerificationNotification::class,
            callback: fn (mixed $notification, array $channels): bool => in_array('mail', $channels),
        );
    }
}
