<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Media;

use App\Actions\Media\DeleteFileAction;
use App\Models\Muscle;
use App\Services\Media\UploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteFileActionTest extends TestCase
{
    use RefreshDatabase;

    private DeleteFileAction $actionUnderTest;
    private UploadService $uploadService;
    private Muscle $mediableModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(DeleteFileAction::class);
        $this->uploadService = app()->make(UploadService::class);
        $this->mediableModel = $this->createMediableModel();
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
