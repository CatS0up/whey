<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DifficultyLevel;
use App\Enums\ExerciseType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

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
        $difficultyLevelValues = Arr::map(
            DifficultyLevel::cases(),
            fn (DifficultyLevel $level): string => $level->value,
        );

        $typeValues = Arr::map(
            ExerciseType::cases(),
            fn (ExerciseType $type): string => $type->value,
        );

        return [
            'name' => fake()->word(),
            'difficulty_level' => fake()->randomElement($difficultyLevelValues),
            'type' => fake()->randomElement($typeValues),
            'instructions_html' => fake()->text(),
            'is_public' => fake()->boolean(),
        ];
    }

    public function withAuthor(): self
    {
        return $this->state(
            fn (array $attributes): array => [
                'author_id' => User::factory()->create()->value('id'),
            ],
        );
    }
}
