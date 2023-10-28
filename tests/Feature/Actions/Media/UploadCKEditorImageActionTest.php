<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Media;

use App\Actions\Media\UploadCKEditorImageAction;
use App\DataObjects\FileData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\Media;
use Tests\TestCase;

class UploadCKEditorImageActionTest extends TestCase
{
    use Media;
    use RefreshDatabase;

    private UploadCKEditorImageAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(UploadCKEditorImageAction::class);
    }

    /**
     * @test
     */
    public function it_should_upload_ckeditor_image(): void
    {
        $image = $this->createTestImage();

        $imageData = $this->actionUnderTest->execute($image);

        Storage::disk(self::TEST_DISK)->assertExists($imageData->path);
    }

    /**
     * @test
     */
    public function it_should_return_correct_file_data_object_when_upload_is_succeed(): void
    {
        $image = $this->createTestImage();

        $actual = $this->actionUnderTest->execute($image);

        // Hash comes from config(app.uploads.hash)
        $expectedHash = $this->createHashFromPath($actual->path);

        $this->assertInstanceOf(FileData::class, $actual);
        $this->assertNull($actual->id);
        $this->assertEquals($image->hashName(), $actual->name);
        $this->assertEquals($image->getClientOriginalName(), $actual->file_name);
        $this->assertEquals($image->getMimeType(), $actual->mime_type);
        $this->assertEquals("ckeditor/{$actual->name}", $actual->path);
        $this->assertEquals(self::TEST_DISK, $actual->disk);
        $this->assertEquals($image->getSize(), $actual->size);
        $this->assertEquals($expectedHash, $actual->file_hash);
        $this->assertEquals('ckeditor', $actual->collection);
    }
}
