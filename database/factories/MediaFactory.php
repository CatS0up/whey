<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $extension = fake()->fileExtension();

        return [
            'name' => fake()->sha256().'.'.$extension,
            'file_name' => fake()->word().'.'.$extension,
            'mime_type' => fake()->mimeType(),
            'path' => 'dummy/path/to/file/'.fake()->word().'.'.$extension,
            'disk' => fake()->word(),
            'file_hash' => fake()->sha256(),
            'collection' => fake()->word(),
            'size' => fake()->numberBetween(),
        ];
    }
}
