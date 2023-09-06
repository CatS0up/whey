<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Media;

use App\Actions\Media\UpsertThumbnailAction;
use App\Models\Contracts\Mediable;
use App\Models\Media;
use App\Services\Media\UploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class UpsertThumbnailActionTest extends TestCase
{
    use RefreshDatabase;

    private UpsertThumbnailAction $actionUnderTest;
    private UploadService $uploadService;
    private Mediable $mediableModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(UpsertThumbnailAction::class);
        $this->uploadService = app()->make(UploadService::class);
        $this->mediableModel = $this->createMediableModel();
    }

    /** @test */
    public function it_should_upsert_target_thumbnail_when_thumbnail_not_exists(): void
    {
        $target = $this->mediableModel;

        $this->assertFalse($target->thumbnail->exists());

        $this->actionUnderTest->execute(
            file: $this->createTestImage(),
            model: $target,
        );

        $this->assertTrue($target->thumbnail->exists());
    }

    /** @test */
    public function it_should_upsert_target_thumbnail_when_thumbnail_exists(): void
    {
        $target = $this->mediableModel;
        $thumbnailData = $this->uploadService->thumbnail($this->createTestImage(), $target);
        $thumbnail = Media::query()->create(Arr::except($thumbnailData->all(), ['id']));
        $target->thumbnail()->save($thumbnail);

        $this->assertTrue($target->thumbnail->exists());

        $newThumbnailFile = $this->createTestImage(
            name: 'new_thumbnail.jpg',
            width: 10,
            height: 10,
        );
        $newThumbnailData = $this->actionUnderTest->execute(
            file: $newThumbnailFile,
            model: $target,
        )
            ->getData();

        $this->assertNotEquals($thumbnailData->path, $newThumbnailData->path);
    }
}
