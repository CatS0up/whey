<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Media;

use App\Actions\Media\UpsertSmallThumbnailAction;
use App\Models\Media;
use App\Models\Muscle;
use App\Services\Media\UploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class UpsertSmallThumbnailActionTest extends TestCase
{
    use RefreshDatabase;

    private UpsertSmallThumbnailAction $actionUnderTest;
    private UploadService $uploadService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(UpsertSmallThumbnailAction::class);
        $this->uploadService = app()->make(UploadService::class);
    }

    /** @test */
    public function it_should_upsert_muscle_small_thumbnail_when_small_thumbnail_not_exists(): void
    {
        // It implements SmallThumbnailInterface
        $muscle = Muscle::factory()->create();

        $this->assertFalse($muscle->smallThumbnail->exists());

        $this->actionUnderTest->execute(
            target: $muscle,
            thumbnail: $this->createTestImage(),
        );

        $this->assertTrue($muscle->smallThumbnail->exists());
    }

    /** @test */
    public function it_should_upsert_muscle_small_thumbnail_when_small_thumbnail_exists(): void
    {
        // It implements SmallThumbnailInterface
        $muscle = Muscle::factory()->create();
        $smallThumbnailData = $this->uploadService->smallThumbnail($this->createTestImage());
        $smallThumbnail = Media::query()->create(Arr::except($smallThumbnailData->all(), ['id']));

        $muscle->smallThumbnail()->save($smallThumbnail);

        $this->assertTrue($muscle->smallThumbnail->exists());

        $newSmallThumbnailFile = $this->createTestImage(
            name: 'new_small_thumbnail.jpg',
            width: 10,
            height: 10,
        );
        $newSmallThumbnailData = $this->actionUnderTest->execute(
            target: $muscle,
            thumbnail: $newSmallThumbnailFile
        )
            ->getData();

        $muscle->smallThumbnail->refresh();

        $this->assertNotEquals($smallThumbnailData->path, $newSmallThumbnailData->path);
    }
}
