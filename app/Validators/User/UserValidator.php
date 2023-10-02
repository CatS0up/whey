<?php

declare(strict_types=1);

namespace App\Validators\User;

use App\Enums\HeightUnit;
use App\Enums\PhoneCountry;
use App\Enums\WeightUnit;
use App\Http\Livewire\Auth\RegisterForm;
use Illuminate\Validation\Rules\Enum;

class UserValidator
{
    public function rulesForRegistrationForm(RegisterForm $context): array
    {
        return [
            1 => [
                ...(new AvatarValidator())->rules(),
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:users,name',
                ],
            ],
            2 => [
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    'unique:users,email',
                ],
                'phone' => [
                    'required',
                    'required_with:phone_country',
                    'phone',
                    'max:32',
                    'unique:users,phone',
                ],
                'phone_country' => [
                    'required',
                    'required_with:phone',
                    'max:3',
                    new Enum(PhoneCountry::class),
                ],
                ...(new PasswordValidator())->rules(),
            ],
            3 => [
                'weight' => [
                    'required',
                    'required_with:weight_unit',
                    'numeric',
                    'min:1',
                ],
                'weight_unit' => [
                    'required',
                    'string',
                    new Enum(WeightUnit::class),
                ],
                'height' => [
                    'required',
                    'required_with:height_unit',
                    'numeric',
                    'min:1',
                ],
                'height_unit' => [
                    'required',
                    'string',
                    new Enum(HeightUnit::class),
                ],
            ],
        ];
    }
}
