<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DifficultyLevel;
use App\Enums\ExerciseType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exercise>
 */
class ExerciseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'difficulty_level' => fake()->randomElement(DifficultyLevel::cases())->value,
            'type' => fake()->randomElement(ExerciseType::cases())->value,
            'instructions_html' => fake()->text(),
            'is_public' => fake()->boolean(),
            'verified_at' => now(),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verified_at' => null,
        ]);
    }
}
