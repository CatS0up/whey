<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DifficultyLevel;
use App\Enums\ExerciseStatus;
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
            'status' => fake()->randomElement(ExerciseStatus::cases())->value,
            'instructions_html' => fake()->text(),
            'is_public' => fake()->boolean(),
            'reviewed_at' => now(),
        ];
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ExerciseStatus::Verified,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ExerciseStatus::Rejected,
        ]);
    }
}
