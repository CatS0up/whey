<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Media;

use App\Actions\Media\UploadThumbnailAction;
use App\DataObjects\FileData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\Media;
use Tests\TestCase;

class UploadThumbnailActionTest extends TestCase
{
    use Media;
    use RefreshDatabase;

    private UploadThumbnailAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(UploadThumbnailAction::class);
    }

    /**
     * @test
     */
    public function it_should_upload_thumbnail(): void
    {
        $thumbnail = $this->createTestImage();

        $thumbnailData = $this->actionUnderTest->execute($thumbnail, $this->mediableModel);

        Storage::disk(self::TEST_DISK)->assertExists($thumbnailData->path);
    }

    /**
     * @test
     */
    public function it_should_return_correct_file_data_object_when_upload_is_succeed(): void
    {
        $thumbnail = $this->createTestImage();

        $actual = $this->actionUnderTest->execute($thumbnail, $this->mediableModel);

        // Hash comes from config(app.uploads.hash)
        $expectedHash = $this->createHashFromPath($actual->path);

        $this->assertInstanceOf(FileData::class, $actual);
        $this->assertNull($actual->id);
        $this->assertEquals($thumbnail->hashName(), $actual->name);
        $this->assertEquals($thumbnail->getClientOriginalName(), $actual->file_name);
        $this->assertEquals($thumbnail->getMimeType(), $actual->mime_type);
        $this->assertEquals("thumbnails/{$this->mediableModel->getSubDirectoryFilePath()}/{$actual->name}", $actual->path);
        $this->assertEquals(self::TEST_DISK, $actual->disk);
        $this->assertEquals($thumbnail->getSize(), $actual->size);
        $this->assertEquals($expectedHash, $actual->file_hash);
        $this->assertEquals('thumbnails', $actual->collection);
    }
}
