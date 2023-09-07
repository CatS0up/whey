<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Media;

use App\Actions\Media\DeleteFileAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\Media;
use Tests\TestCase;

class DeleteFileActionTest extends TestCase
{
    use Media;
    use RefreshDatabase;

    private DeleteFileAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(DeleteFileAction::class);
    }

    /**
     * @test
     */
    public function it_should_delete_thumbnail(): void
    {
        $thumbnail = $this->createTestImage();
        $thumbnailData = $this->uploadService->thumbnail($thumbnail, $this->mediableModel);

        Storage::disk(self::TEST_DISK)->assertExists($thumbnailData->path);

        $isDeleted = $this->actionUnderTest->execute($thumbnailData);

        $this->assertTrue($isDeleted);
        Storage::disk(self::TEST_DISK)->assertMissing($thumbnailData->path);
    }
}
