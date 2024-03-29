<?php

declare(strict_types=1);

namespace App\Http\Livewire\Auth;

use App\Actions\Auth\RegisterAction;
use App\DataObjects\Auth\RegistrationData;
use App\Enums\HeightUnit;
use App\Enums\PhoneCountry;
use App\Enums\SweetAlertToastType;
use App\Enums\WeightUnit;
use App\Http\Livewire\MultiStepForm;
use App\Providers\RouteServiceProvider;
use App\Validators\Auth\RegisterValidator;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Livewire\Redirector;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class RegisterForm extends MultiStepForm
{
    use WithFileUploads;

    protected $listeners = [
        'recaptcha-ready' => 'register',
    ];

    protected int $minStep = 1;
    protected int $maxStep = 3;

    private RegisterAction $formAction;

    // Step 1 - start
    public null|TemporaryUploadedFile|string $avatar = null;
    public string $name;
    // Step 1 - end

    // Step 2 - start
    public ?string $email = null;
    public ?string $phone = null;
    public string $phone_country = PhoneCountry::Poland->value;
    public string $password;
    public string $password_confirmation;
    // Step 2 - end

    // Step 3 - start
    public float $weight;
    public string $weight_unit = WeightUnit::Kilograms->value;
    public float $height;
    public string $height_unit = HeightUnit::Centimeters->value;
    public string $recaptcha;
    // Step 3 - end

    public function boot(
        RegisterAction $formAction,
    ): void {
        $this->formAction = $formAction;
    }

    public function getAvatarPathProperty(): array
    {
        if ($this->avatar instanceof TemporaryUploadedFile) {
            return [
                $this->avatar->temporaryUrl(),
            ];
        }

        return [];
    }

    /**
     * This is the location for the registration logic, which is not included in the submit method.
     * The registration logic is executed after the form is accepted and the Recaptcha triggers the 'recaptcha-ready' event,
     * which initiates the actual registration process.
     */
    public function register(): Redirector|RedirectResponse
    {
        $this->validate();

        $this->formAction->execute(RegistrationData::createFromArray(
            [
                // Step 1 - start
                'avatar' => $this->avatar,
                'name' => $this->name,
                // Step 1 - end

                // Step 2 - start
                'email' => $this->email,
                'phone' => $this->phone,
                'phone_country' => $this->phone_country,
                'password' => $this->password,
                // Step 2 - end

                // Step 3 - start
                'weight' => $this->weight,
                'weight_unit' => $this->weight_unit,
                'height' => $this->height,
                'height_unit' => $this->height_unit,
                // Step 3 - end
            ]
        ));

        return to_route(RouteServiceProvider::GUEST_USER_HOME)
        // TODO: Tłumaczenia
            ->with(
                key: SweetAlertToastType::Success->type(),
                value: 'Activation email has been sent',
            );
    }

    /**
     * Trigger the execution of Recaptcha v3, which is asynchronous.
     * After processing the Recaptcha, the 'recaptcha-ready' event is invoked,
     * allowing the registration process to continue.
     *
     * This method is marked as abstract because it is inherited from the abstract class MultiStepForm,
     * and must be implemented in concrete subclasses.
     */
    public function submit(): mixed
    {
        return null;
    }

    public function render(): View
    {
        return view('livewire.auth.register-form');
    }

    protected function rulesForStep(): array
    {
        return (new RegisterValidator())->rulesForRegistrationForm($this);
    }
}
