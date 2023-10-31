<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Media;

use App\Actions\Media\UpsertSmallThumbnailAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Media;
use Tests\Concerns\Media as HasMedia;
use Tests\TestCase;

class UpsertSmallThumbnailActionTest extends TestCase
{
    use HasMedia;
    use RefreshDatabase;

    private UpsertSmallThumbnailAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(UpsertSmallThumbnailAction::class);
    }

    /** @test */
    public function it_should_upsert_target_small_thumbnail_when_small_thumbnail_not_exists(): void
    {
        // It implements SmallThumbnailInterface
        $target = $this->mediableModel;

        $this->assertFalse($target->smallThumbnail()->exists());

        $this->actionUnderTest->execute(
            file: $this->createTestImage(),
            model: $target,
        );

        $this->assertTrue($target->smallThumbnail->exists());
    }

    /** @test */
    public function it_should_upsert_target_small_thumbnail_when_small_thumbnail_exists(): void
    {
        $target = $this->mediableModel;
        $smallThumbnailData = $this->uploadService->smallThumbnail($this->createTestImage(), $target);
        $smallThumbnail = Media::query()->create($smallThumbnailData->allForUpsert());
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
