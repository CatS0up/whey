<?php

namespace Tests\Feature\Actions\Media;

use App\Actions\Media\UploadAvatarAction;
use App\DataObjects\FileData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\Media;
use Tests\TestCase;

class UploadAvatarActionTest extends TestCase
{
    use Media;
    use RefreshDatabase;

    private UploadAvatarAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(UploadAvatarAction::class);
    }

    /**
     * @test
     */
    public function it_should_upload_avatar(): void
    {
        $avatar = $this->createTestImage();

        $avatarData = $this->actionUnderTest->execute($avatar, $this->mediableModel);

        Storage::disk(self::TEST_DISK)->assertExists($avatarData->path);
    }

    /**
     * @test
     */
    public function it_should_return_correct_file_data_object_when_upload_is_succeed(): void
    {
        $avatar = $this->createTestImage();

        $actual = $this->actionUnderTest->execute($avatar, $this->mediableModel);

        // Hash comes from config(app.uploads.hash)
        $expectedHash = hash_file(
            'sha256',
            Storage::disk(self::TEST_DISK)->path($actual->path),
        );

        $this->assertInstanceOf(FileData::class, $actual);
        $this->assertNull($actual->id);
        $this->assertEquals($avatar->hashName(), $actual->name);
        $this->assertEquals($avatar->getClientOriginalName(), $actual->file_name);
        $this->assertEquals($avatar->getMimeType(), $actual->mime_type);
        $this->assertEquals("avatars/{$this->mediableModel->getSubDirectoryFilePath()}/{$actual->name}", $actual->path);
        $this->assertEquals(self::TEST_DISK, $actual->disk);
        $this->assertEquals($avatar->getSize(), $actual->size);
        $this->assertEquals($expectedHash, $actual->file_hash);
        $this->assertEquals('avatars', $actual->collection);
    }
}
