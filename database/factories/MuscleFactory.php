<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MuscleGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Muscle>
 */
class MuscleFactory extends Factory
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
            'muscle_group' => fake()->randomElement(MuscleGroup::cases())->value,
        ];
    }
}
