<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Media;

use App\Actions\Media\UploadSmallThumbnailAction;
use App\DataObjects\FileData;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadSmallThumbnailActionTest extends TestCase
{
    private UploadSmallThumbnailAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(UploadSmallThumbnailAction::class);
    }

    /**
     * @test
     */
    public function it_should_upload_small_thumbnail(): void
    {
        $smallThumbnail = $this->createTestImage();

        $smallThumbnailData = $this->actionUnderTest->execute($smallThumbnail);

        Storage::disk(self::TEST_DISK)->assertExists($smallThumbnailData->path);
    }

    /**
     * @test
     */
    public function it_should_return_correct_file_data_object_when_upload_is_succeed(): void
    {
        $smallThumbnail = $this->createTestImage();

        $actual = $this->actionUnderTest->execute($smallThumbnail);

        // Hash comes from config(app.uploads.hash)
        $expectedHash = hash_file(
            'sha256',
            Storage::disk(self::TEST_DISK)->path($actual->path),
        );

        $this->assertInstanceOf(FileData::class, $actual);
        $this->assertNull($actual->id);
        $this->assertEquals($smallThumbnail->hashName(), $actual->name);
        $this->assertEquals($smallThumbnail->getClientOriginalName(), $actual->file_name);
        $this->assertEquals($smallThumbnail->getMimeType(), $actual->mime_type);
        $this->assertEquals("small_thumbnails/{$actual->name}", $actual->path);
        $this->assertEquals(self::TEST_DISK, $actual->disk);
        $this->assertEquals($smallThumbnail->getSize(), $actual->size);
        $this->assertEquals($expectedHash, $actual->hash);
        $this->assertEquals('small_thumbnails', $actual->collection);
    }
}
