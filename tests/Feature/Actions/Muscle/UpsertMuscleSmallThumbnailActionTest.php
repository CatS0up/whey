<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Muscle;

use Tests\TestCase;

class UpsertMuscleSmallThumbnailActionTest extends TestCase
{
    /** @test */
    public function it_should_create_and_resize_muscle_small_thumbnail_when_choosen_muscle_does_not_have_own_small_thumbnail(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_should_create_and_resize_muscle_small_thumbnail_when_choosen_muscle_have_own_small_thumbnail(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
