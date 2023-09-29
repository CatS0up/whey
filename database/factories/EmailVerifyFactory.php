<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmailVerify>
 */
class EmailVerifyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'token' => str()->random(),
            'expire_at' => fake()->dateTime(now()->addMinutes(30)),
        ];
    }

    public function active(): self
    {
        return $this->state(fn (array $attributes) => [
            'expire_at' => now()->addMinutes(
                config('auth.email_verify.token_lifetime'),
            ),
        ]);
    }
}
