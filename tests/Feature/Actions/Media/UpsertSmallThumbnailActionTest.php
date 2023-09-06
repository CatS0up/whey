<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Media;

use App\Actions\Media\UpsertSmallThumbnailAction;
use App\Models\Contracts\Mediable;
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
    private Mediable $mediableModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(UpsertSmallThumbnailAction::class);
        $this->uploadService = app()->make(UploadService::class);
        $this->mediableModel = $this->createMediableModel();
    }

    /** @test */
    public function it_should_upsert_target_small_thumbnail_when_small_thumbnail_not_exists(): void
    {
        // It implements SmallThumbnailInterface
        $target = Muscle::factory()->create();

        $this->assertFalse($target->smallThumbnail->exists());

        $this->actionUnderTest->execute(
            file: $this->createTestImage(),
            model: $target,
        );

        $this->assertTrue($target->smallThumbnail->exists());
    }

    /** @test */
    public function it_should_upsert_target_small_thumbnail_when_small_thumbnail_exists(): void
    {
        // It implements SmallThumbnailInterface
        $target = Muscle::factory()->create();
        $smallThumbnailData = $this->uploadService->smallThumbnail($this->createTestImage(), $target);
        $smallThumbnail = Media::query()->create(Arr::except($smallThumbnailData->all(), ['id']));
        $target->smallThumbnail()->save($smallThumbnail);

        $this->assertTrue($target->smallThumbnail->exists());

        $newSmallThumbnailFile = $this->createTestImage(
            name: 'new_small_thumbnail.jpg',
            width: 10,
            height: 10,
        );
        $newSmallThumbnailData = $this->actionUnderTest->execute(
            file: $newSmallThumbnailFile,
            model: $target,
        )
            ->getData();

        $this->assertNotEquals($smallThumbnailData->path, $newSmallThumbnailData->path);
    }
}
