<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permission>
 */
class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(asText: true),
            /**
             * Slug is generated by Sluggable trait
             *
             * @see App\Models\Concerns\Sluggable
             * @see App\Models\Permission
             */
            // 'slug' => 'slug-from-name',
        ];
    }
}