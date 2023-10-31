<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Muscle;

use App\Actions\Muscle\UpsertMuscleSmallThumbnailAction;
use App\Models\Media;
use App\Models\Muscle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\Concerns\Media as HasMedia;
use Tests\TestCase;

class UpsertMuscleSmallThumbnailActionTest extends TestCase
{
    use HasMedia;
    use RefreshDatabase;

    private UpsertMuscleSmallThumbnailAction $actionUnderTest;
    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(UpsertMuscleSmallThumbnailAction::class);
    }

    /** @test */
    public function it_should_create_and_resize_muscle_small_thumbnail_when_choosen_muscle_does_not_has_own_small_thumbnail(): void
    {
        $muscle = Muscle::factory()->create();
        $smallThumbnail = $this->createTestImage(width: 20, height: 20);

        $this->assertFalse($muscle->smallThumbnail()->exists());

        $upsertedSmallThumbnail = $this->actionUnderTest->execute($muscle->id, $smallThumbnail);
        $dimension = $this->getImageDimensionInfo($upsertedSmallThumbnail->getData());

        $this->assertTrue($muscle->smallThumbnail()->exists());
        $this->assertEquals(50, $dimension['width']);
        $this->assertEquals(50, $dimension['height']);
    }

    /** @test */
    public function it_should_create_and_resize_muscle_small_thumbnail_when_choosen_muscle_has_own_small_thumbnail(): void
    {
        $muscle = Muscle::factory()->create();
        $smallThumbnailData = $this->uploadService->thumbnail($this->createTestImage(), $muscle);
        $smallThumbnail = Media::query()->create($smallThumbnailData->allForUpsert());
        $muscle->thumbnail()->save($smallThumbnail);

        $this->assertTrue($muscle->smallThumbnail->exists());

        $newThumbnail = $this->createTestImage(width: 20, height: 20);
        $upsertedSmallThumbnail = $this->actionUnderTest->execute($muscle->id, $newThumbnail);
        $newSmallThumbnailData = $upsertedSmallThumbnail->getData();
        $dimension = $this->getImageDimensionInfo($newSmallThumbnailData);

        $this->assertTrue($muscle->thumbnail->exists());
        $this->assertEquals(50, $dimension['width']);
        $this->assertEquals(50, $dimension['height']);

        $this->assertNotEquals($smallThumbnailData->name, $newSmallThumbnailData->name);
    }
}
