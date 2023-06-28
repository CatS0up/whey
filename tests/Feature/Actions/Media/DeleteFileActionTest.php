<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Media;

use App\Actions\Media\DeleteFileAction;
use App\Services\Media\UploadService;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteFileActionTest extends TestCase
{
    private DeleteFileAction $actionUnderTest;
    private UploadService $uploadService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(DeleteFileAction::class);
        $this->uploadService = app()->make(UploadService::class);
    }

    /**
     * @test
     */
    public function it_should_delete_thumbnail(): void
    {
        $thumbnail = $this->createTestImage();
        $thumbnailData = $this->uploadService->thumbnail($thumbnail);

        Storage::disk(self::TEST_DISK)->assertExists($thumbnailData->path);

        $isDeleted = $this->actionUnderTest->execute($thumbnailData);

        $this->assertTrue($isDeleted);
        Storage::disk(self::TEST_DISK)->assertMissing($thumbnailData->path);
    }
}
