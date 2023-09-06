<?php

declare(strict_types=1);

namespace Tests;

use App\DataObjects\FileData;
use App\Models\Contracts\Mediable;
use App\Models\Muscle;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /** @var string */
    protected const TEST_DISK = 'testing_disk';
    /** @var string */
    protected const TEST_IMAGE_NAME = 'testing_image.jpg';
    /** @var int */
    protected const DEFAULT_TEST_IMAGE_WIDTH = 100;
    /** @var int */
    protected const DEFAULT_TEST_IMAGE_HEIGHT = 150;

    protected function setUp(): void
    {
        parent::setUp();

        $this->prepareTestDisk();
    }

    protected function prepareTestDisk(): void
    {
        Storage::fake(self::TEST_DISK);

        // Set testing drive for all uploading files
        config()->set('app.uploads.disk', self::TEST_DISK);
    }

    protected function createTestImage(
        $name = self::TEST_IMAGE_NAME,
        $width = self::DEFAULT_TEST_IMAGE_WIDTH,
        $height = self::DEFAULT_TEST_IMAGE_HEIGHT,
    ): UploadedFile {
        return UploadedFile::fake()->image(
            name: $name,
            width: $width,
            height: $height,
        );
    }

    protected function readFullImagePath(FileData $fileData): string
    {
        return Storage::disk($fileData->disk)->path($fileData->path);
    }

    protected function getImageDimensionInfo(FileData $fileData): array
    {
        $fullPath = $this->readFullImagePath($fileData);
        $imageInfo = getimagesize($fullPath);

        return [
            'width' => $imageInfo[0],
            'height' => $imageInfo[1],
        ];
    }

    protected function createMediableModel(): Mediable|Muscle
    {
        return Muscle::factory()->create();
    }

    protected function createUser(): User
    {
        return User::factory()->create();
    }
}
