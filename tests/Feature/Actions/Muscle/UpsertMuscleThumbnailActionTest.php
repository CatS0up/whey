<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Muscle;

use App\Actions\Muscle\UpsertMuscleThumbnailAction;
use App\Models\Media;
use App\Models\Muscle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Tests\Concerns\Media as HasMedia;

class UpsertMuscleThumbnailActionTest extends TestCase
{
    use HasMedia;
    use RefreshDatabase;

    private UpsertMuscleThumbnailAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(UpsertMuscleThumbnailAction::class);
    }

    /** @test */
    public function it_should_create_and_resize_muscle_thumbnail_when_choosen_muscle_does_not_has_own_thumbnail(): void
    {
        $muscle = Muscle::factory()->create();
        $thumbnail = $this->createTestImage(width: 20, height: 20);

        $this->assertFalse($muscle->thumbnail()->exists());

        $upsertedThumbnail = $this->actionUnderTest->execute($muscle->id, $thumbnail);
        $dimension = $this->getImageDimensionInfo($upsertedThumbnail->getData());

        $this->assertTrue($muscle->thumbnail()->exists());
        $this->assertEquals(200, $dimension['width']);
        $this->assertEquals(200, $dimension['height']);
    }

    /** @test */
    public function it_should_create_and_resize_muscle_thumbnail_when_choosen_muscle_has_own_thumbnail(): void
    {
        $muscle = Muscle::factory()->create();
        $thumbnailData = $this->uploadService->thumbnail($this->createTestImage(), $muscle);
        $thumbnail = Media::query()->create(Arr::except($thumbnailData->all(), ['id']));
        $muscle->thumbnail()->save($thumbnail);

        $this->assertTrue($muscle->thumbnail->exists());

        $newThumbnail = $this->createTestImage(width: 20, height: 20);
        $upsertedThumbnail = $this->actionUnderTest->execute($muscle->id, $newThumbnail);
        $newThumbnailData = $upsertedThumbnail->getData();
        $dimension = $this->getImageDimensionInfo($newThumbnailData);

        $this->assertTrue($muscle->thumbnail->exists());
        $this->assertEquals(200, $dimension['width']);
        $this->assertEquals(200, $dimension['height']);

        $this->assertNotEquals($thumbnailData->name, $newThumbnailData->name);
    }
}
