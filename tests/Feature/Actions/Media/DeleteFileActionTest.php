<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Media;

use App\Actions\Media\DeleteFileAction;
use App\Actions\Media\UploadThumbnailAction;
use App\Services\Media\UploadService;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteFileActionTest extends TestCase
{
    private DeleteFileAction $actionUnderTest;
    private UploadService $uploadService;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake(self::TEST_DISK);

        $this->actionUnderTest = new DeleteFileAction(
            manager: app()->make(FilesystemManager::class),
        );

        $this->uploadService = new UploadService(
            uploadThumbnailAction: new UploadThumbnailAction(
                app()->make(FilesystemManager::class),
                self::TEST_DISK,
            ),
        );
    }

    /**
     * @test
     */
    public function it_should_delete_thumbnail(): void
    {
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');
        $thumbnailData = $this->uploadService->thumbnail($thumbnail);

        Storage::disk(self::TEST_DISK)->assertExists($thumbnailData->path);

        $isDeleted = $this->actionUnderTest->execute($thumbnailData);

        $this->assertTrue($isDeleted);
        Storage::disk(self::TEST_DISK)->assertMissing($thumbnailData->path);
    }
}
