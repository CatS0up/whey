<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\HeightUnit;
use App\Enums\PhoneCountry;
use App\Enums\WeightUnit;
use App\ValueObjects\Shared\Height;
use App\ValueObjects\Shared\Weight;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => phone(
                number: fake('pl_PL')->unique()->e164PhoneNumber(),
                country: PhoneCountry::Poland->value,
            ),
            /** @see \App\Observers\UserObserver::upsertPhoneFields() */
            // 'phone_country' => 'PL',
            'email_verified_at' => now(),
            'password' => 'password',
            'remember_token' => Str::random(10),
            'weight' => Weight::fromValueAndUnit(
                value: fake()->randomFloat(min: 1),
                unit: fake()->randomElement(WeightUnit::cases()),
            ),
            'height' => Height::fromValueAndUnit(
                value: fake()->randomFloat(min: 1),
                unit: fake()->randomElement(HeightUnit::cases()),
            ),
            /** @see \App\Observers\UserObserver::upsertBmiField() */
            // 'bmi' => 21.0,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
