<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Media;

use App\Actions\Media\UploadThumbnailAction;
use App\DataObjects\FileData;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadThumbnailActionTest extends TestCase
{
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

        $thumbnailData = $this->actionUnderTest->execute($thumbnail);

        Storage::disk(self::TEST_DISK)->assertExists($thumbnailData->path);
    }

    /**
     * @test
     */
    public function it_should_return_correct_file_data_object_when_upload_is_succeed(): void
    {
        $thumbnail = $this->createTestImage();

        $actual = $this->actionUnderTest->execute($thumbnail);

        // Hash comes from config(app.uploads.hash)
        $expectedHash = hash_file(
            'sha256',
            Storage::disk(self::TEST_DISK)->path($actual->path),
        );

        $this->assertInstanceOf(FileData::class, $actual);
        $this->assertNull($actual->id);
        $this->assertEquals($thumbnail->hashName(), $actual->name);
        $this->assertEquals($thumbnail->getClientOriginalName(), $actual->file_name);
        $this->assertEquals($thumbnail->getMimeType(), $actual->mime_type);
        $this->assertEquals("thumbnails/{$actual->name}", $actual->path);
        $this->assertEquals(self::TEST_DISK, $actual->disk);
        $this->assertEquals($thumbnail->getSize(), $actual->size);
        $this->assertEquals($expectedHash, $actual->file_hash);
        $this->assertEquals('thumbnails', $actual->collection);
    }
}
