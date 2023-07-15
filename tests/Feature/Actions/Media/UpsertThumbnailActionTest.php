<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Muscle;

use App\Actions\Media\UpsertThumbnailAction;
use App\Models\Media;
use App\Models\Muscle;
use App\Services\Media\UploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class UpsertThumbnailActionTest extends TestCase
{
    use RefreshDatabase;

    private UpsertThumbnailAction $actionUnderTest;
    private UploadService $uploadService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(UpsertThumbnailAction::class);
        $this->uploadService = app()->make(UploadService::class);
    }

    /** @test */
    public function it_should_upsert_muscle_thumbnail_when_thumbnail_not_exists(): void
    {
        // It implements ThumbnailInterface
        $muscle = Muscle::factory()->create();

        $this->assertFalse($muscle->thumbnail->exists());

        $this->actionUnderTest->execute(
            target: $muscle,
            thumbnail: $this->createTestImage(),
        );

        $this->assertTrue($muscle->thumbnail->exists());
    }

    /** @test */
    public function it_should_upsert_muscle_thumbnail_when_thumbnail_exists(): void
    {
        // It implements ThumbnailInterface
        $muscle = Muscle::factory()->create();
        $thumbnailData = $this->uploadService->thumbnail($this->createTestImage());
        $thumbnail = Media::query()->create(Arr::except($thumbnailData->all(), ['id']));

        $muscle->thumbnail()->save($thumbnail);

        $this->assertTrue($muscle->thumbnail->exists());

        $newThumbnailFile = $this->createTestImage(
            name: 'new_thumbnail.jpg',
            width: 10,
            height: 10,
        );
        $newThumbnailData = $this->actionUnderTest->execute(
            target: $muscle,
            thumbnail: $newThumbnailFile
        )
            ->getData();

        $muscle->thumbnail->refresh();

        $this->assertNotEquals($thumbnailData->path, $newThumbnailData->path);
    }
}