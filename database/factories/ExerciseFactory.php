<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DifficultyLevel;
use App\Enums\ExerciseStatus;
use App\Enums\ExerciseType;
use App\Models\User;
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
            'instructions_html' => fake()->randomHtml(),
            'is_public' => fake()->boolean(),
            'author_id' => User::factory()->create()->id,
        ];
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ExerciseStatus::Verified,
            'reviewed_at' => now(),
            'reviewer_id' => User::factory()->create()->id,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ExerciseStatus::Rejected,
            'reviewed_at' => now(),
            'reviewer_id' => User::factory()->create()->id,
        ]);
    }
}
