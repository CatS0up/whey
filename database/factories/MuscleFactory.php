<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MuscleGroup;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

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
        $groupValues = Arr::map(
            MuscleGroup::cases(),
            fn (MuscleGroup $group): string => $group->value,
        );
        return [
            'name' => fake()->word(),
            'description' => fake()->text(),
            'muscle_group' => fake()->randomElement($groupValues),
        ];
    }
}
