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
use App\Validators\Auth\RegisterValidator;
use Illuminate\View\View;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class RegisterForm extends MultiStepForm
{
    use WithFileUploads;

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

    public function submit(): mixed
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

        return to_route('auth.login.show')
            // TODO: Tłumaczenia
            ->with(
                key: SweetAlertToastType::Success->type(),
                value: 'Activation email has been sent',
            );
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
